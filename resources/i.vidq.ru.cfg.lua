plugin_paths = { "/usr/share/jitsi-meet/prosody-plugins/" }

-- domain mapper options, must at least have domain base set to use the mapper
muc_mapper_domain_base = "i.vidq.ru";

turncredentials_secret = "SecretPassword";

turncredentials = {
  { type = "stun", host = "vidq.ru", port = "4446" },
  { type = "turn", host = "vidq.ru", port = "4446", transport = "udp" },
  { type = "turns", host = "vidq.ru", port = "443", transport = "tcp" }
};

cross_domain_bosh = false;
consider_bosh_secure = true;

-- Guest Virtualhost
VirtualHost "guest.vidq.ru"
        authentication = "anonymous"
        c2s_require_encryption = false

VirtualHost "i.vidq.ru"
        authentication = "internal_plain"
        storage = {
            accounts = "sql",
        }
        sql = {
            driver = "MySQL", -- May also be "MySQL" or "SQLite3" (case sensitive!)
            database = "jitsi", -- The database name to use. 
            host = "db.vidq.ru", -- The address of the database server (delete this line for Postgres)
            port = 3306, -- For databases connecting over TCP
            username = "admin", -- The username to authenticate to the database
            password = "SecretPassword" -- The password to authenticate to the database
        }
        -- enabled = false -- Remove this line to enable this host
        -- authentication = "anonymous"
        -- Properties below are modified by jitsi-meet-tokens package config
        -- and authentication above is switched to "token"
        --app_id="example_app_id"
        --app_secret="example_app_secret"
        -- Assign this host a certificate for TLS, otherwise it would use the one
        -- set in the global section (if any).
        -- Note that old-style SSL on port 5223 only supports one certificate, and will always
        -- use the global one.
        ssl = {
                key = "/etc/prosody/certs/i.vidq.ru.key";
                certificate = "/etc/prosody/certs/i.vidq.ru.crt";
        }
        speakerstats_component = "speakerstats.i.vidq.ru"
        conference_duration_component = "conferenceduration.i.vidq.ru"
        -- we need bosh
        modules_enabled = {
            "bosh";
            "pubsub";
            "ping"; -- Enable mod_ping
            "speakerstats";
            "turncredentials";
            "conference_duration";
        }
        c2s_require_encryption = false

Component "conference.i.vidq.ru" "muc"
    storage = "memory"
    -- customization
    name = "Conference System"
    user_admin = "admin@vidq.ru"
    max_history_messages = 120
    muc_room_default_history_length = 120
    muc_room_default_language = "ru"
    muc_room_default_change_subject = false
    muc_room_default_members_only = false
    muc_room_default_public = false
    muc_tombstones = false
    restrict_room_creation = true
    sql = {
        driver = "MySQL", -- May also be "MySQL" or "SQLite3" (case sensitive!)
        database = "jitsi", -- The database name to use. 
        host = "db.vidq.ru", -- The address of the database server (delete this line for Postgres)
        port = 3306, -- For databases connecting over TCP
        username = "admin", -- The username to authenticate to the database
        password = "SecretPassword" -- The password to authenticate to the database
    }
    storage = {
        muc_management = "sql"
    }
    muc_config_restricted = {
        "muc#roomconfig_roomsecret",
        "muc#roomconfig_persistentroom"
    }
    modules_enabled = {
        "muc_management",
        -- eoc
        "muc_meeting_id";
        "muc_domain_mapper";
        -- "token_verification";
    }
    admins = { "focus@auth.i.vidq.ru" }
    muc_room_locking = false
    muc_room_default_public_jids = true

-- internal muc component
Component "internal.auth.i.vidq.ru" "muc"
    storage = "memory"
    modules_enabled = {
      "ping";
    }
    admins = { "focus@auth.i.vidq.ru", "jvb@auth.i.vidq.ru" }
    muc_room_locking = false
    muc_room_default_public_jids = true

VirtualHost "auth.i.vidq.ru"
    ssl = {
        key = "/etc/prosody/certs/auth.i.vidq.ru.key";
        certificate = "/etc/prosody/certs/auth.i.vidq.ru.crt";
    }
    authentication = "internal_plain"
    -- custom
    storage = {
        accounts = "sql",
    }
    sql = {
        driver = "MySQL", -- May also be "MySQL" or "SQLite3" (case sensitive!)
        database = "jitsi", -- The database name to use. 
        host = "db.vidq.ru", -- The address of the database server (delete this line for Postgres)
        port = 3306, -- For databases connecting over TCP
        username = "admin", -- The username to authenticate to the database
        password = "SecretPassword" -- The password to authenticate to the database
    }

Component "focus.i.vidq.ru"
    component_secret = "SecretPassword"

Component "speakerstats.i.vidq.ru" "speakerstats_component"
    muc_component = "conference.i.vidq.ru"

Component "conferenceduration.i.vidq.ru" "conference_duration_component"
    muc_component = "conference.i.vidq.ru"
