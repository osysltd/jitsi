<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prosody extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sort_id';


    /**
     * Sets the user password.
     *
     * @return void
     */

    public static function setUserPassword()
    { }

    /*
function provider.set_password(username, password)
	log("debug", "set_password for username '%s'", username);
	local account = accounts:get(username);
	if account then
		account.salt = generate_uuid();
		account.iteration_count = max(account.iteration_count or 0, default_iteration_count);
		local valid, stored_key, server_key = get_auth_db(password, account.salt, account.iteration_count);
		local stored_key_hex = to_hex(stored_key);
		local server_key_hex = to_hex(server_key);

		account.stored_key = stored_key_hex
		account.server_key = server_key_hex

		account.password = nil;
		return accounts:set(username, account);
	end
	return nil, "Account not available.";
end


local function generate()
	-- generate RFC 4122 complaint UUIDs (version 4 - random)
	return get_nibbles(8).."-"..get_nibbles(4).."-4"..get_nibbles(3).."-"..(get_twobits())..get_nibbles(3).."-"..get_nibbles(12);
end

local hex = require"util.hex";
local to_hex, from_hex = hex.to, hex.from;

local char_to_hex = {};
local hex_to_char = {};

do
	local char, hex;
	for i = 0,255 do
		char, hex = s_char(i), s_format("%02x", i);
		char_to_hex[char] = hex;
		hex_to_char[hex] = char;
	end
end

local function to(s)
	return (s_gsub(s, ".", char_to_hex));
end

local function from(s)
	return (s_gsub(s_lower(s), "%X*(%x%x)%X*", hex_to_char));
end

*/
}
