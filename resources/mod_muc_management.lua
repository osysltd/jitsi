-- /usr/lib/prosody/modules/mod_muc_management.lua
-- Conference management
-- https://github.com/osysltd

local user_admin = module:get_option_string("user_admin")
local restricted_options = module:get_option_set("muc_config_restricted", {})._items
local muc_max_age = module:get_option_number("muc_max_age", 31556900) --7890000 3months 31556900 1 year
local muc_timer_repeat = module:get_option_string("muc_timer_repeat", 2592000) -- 259200 3days 25920000 30days
local rooms = module:open_store()
local timer = require "util.timer"
local is_admin = require "core.usermanager".is_admin
local split_jid = require "util.jid".split
local t_remove = table.remove

local st = require "util.stanza"
function send_message(jid, subject, message)
    if jid then
        local msg =
            st.message({from = module.host, to = jid}):tag("subject"):text(subject):up():tag("body"):text(message)
        module:send(msg)
    end
end

-- https://rosettacode.org/wiki/Password_generator#Lua
function randPW(length)
    math.randomseed(os.time())
    local index, pw, rnd = 0, ""
    local chars = {
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        "abcdefghijklmnopqrstuvwxyz",
        "123456789"
    }
    repeat
        index = index + 1
        rnd = math.random(chars[index]:len())
        if math.random(2) == 1 then
            pw = pw .. chars[index]:sub(rnd, rnd)
        else
            pw = chars[index]:sub(rnd, rnd) .. pw
        end
        index = index % #chars
    until pw:len() >= length
    return pw
end

function is_room_expired(room)
    local room_info = rooms:get(room)
    if not room_info then
        return nil
    end
    if os.difftime(os.time(), room_info.accessed_at) < muc_max_age then
        return false
    end
    module:log("info", "MUC '%s' expired, was last accessed at '%s'", room, room_info.accessed_at)
    rooms:set(room, {password = nil, accessed_at = nil})
    return true
end

expire_rooms = function()
    module:log("info", "Running MUC rooms collection sanity check every %s seconds", muc_timer_repeat)
    for room in rooms:users() do
        local room_info = rooms:get(room)
        module:log("info", "Checking MUC room '%s' which was last accessed at '%s'", room, room_info.generated_at)
        if is_room_expired(room) then
            module:log("info", "Flushing expired MUC room '%s'", room)
            rooms:set(room, {password = nil, accessed_at = nil})
        end
    end
    return muc_timer_repeat
end

function set_password(muc)
    local room, err = rooms:get(split_jid(muc.jid))
    if room and not is_room_expired(room) then
        local password = room.password
        ok, err = muc:set_password(password)
        if ok then
            return password, nil
        end
    else
        local password = randPW(8)
        ok, err = muc:set_password(password)
        if ok then
            return password, nil
        end
    end
    local msg = string.format("Error using set_password for %s", tostring(muc))
    send_message(user_admin, module.name, msg)
    module:log("error", msg)
    return nil, true
end

--timer.add_task(muc_timer_repeat, expire_rooms)

function handle_room_creation(event)
    if string.sub(event.room.jid, 1, 31) ~= "org.jitsi.jicofo.health.health-" then
        module:log("debug", "Hooked muc-room-created for %s", tostring(event.room))
        local password, err = set_password(event.room)
        if password and rooms:set(split_jid(event.room.jid), {password = password, accessed_at = os.time()}) then
            local msg = string.format("%s password '%s'", tostring(event.room), password)
            send_message(user_admin, module.name, msg)
            module:log("info", msg)
            return nil
        end
        local msg =
            string.format(
            "Error opening module '%s' storage for %s, revoking password",
            module.name,
            tostring(event.room)
        )
        send_message(user_admin, module.name, msg)
        module:log("error", msg)
        event.room:set_password(nil)
        return nil
    end
end

function handle_config_request(event)
    module:log(
        "debug",
        "Hooked muc-config-form with actor %s (admin: %s) for %s",
        event.actor,
        is_admin(event.actor, module.host),
        tostring(event.room)
    )
    if is_admin(event.actor, module.host) then
        return
    end -- Don't restrict admins
    local form = event.form
    for i = #form, 1, -1 do
        if restricted_options[form[i].name] then
            t_remove(form, i)
        end
    end
end

function handle_config_submit(event)
    local stanza = event.stanza
    module:log(
        "debug",
        "Hooked muc-config-submitted with actor %s (admin: %s) for %s",
        event.actor,
        is_admin(stanza.attr.from, module.host),
        tostring(event.room)
    )
    if is_admin(stanza.attr.from, module.host) then
        return
    end -- Don't restrict admins
    local fields = event.fields
    for option in restricted_options do
        fields[option] = nil -- Like it was never there
    end
end

module:hook("muc-room-created", handle_room_creation)
module:hook("muc-config-submitted", handle_config_submit)
module:hook("muc-config-form", handle_config_request)
