import sys
import json
import requests
import base64
from enum import Enum
from jwt import JWT, jwk_from_dict

# url to our tenant's signing keys
USM_TENANT_ID = "02625676-3227-440a-9c68-bb6d29de4206"
MICROSOFT_SIGNING_KEYS_URL = f"https://login.microsoftonline.com/{USM_TENANT_ID}/discovery/v2.0/keys"


# different messages according to validation results
class ValidationMsg(Enum):
    SUCCESS = 101
    FAILED = 102
    NO_KEY_FOUND = 103


# get all microsoft pub keys for our tenant
def get_microsoft_pub_keys():
    # get json with all pub keys for verification
    header = {}
    res = requests.get(MICROSOFT_SIGNING_KEYS_URL, headers = header)

    # request is successful
    if res.status_code == 200:
        return json.loads(res.text)

    raise Exception(f"Error while attempting to retrieve public keys from '{MICROSOFT_SIGNING_KEYS_URL}'")

    return


# get kid from jwt header
def get_kid(jwt):
    jwt_header = jwt.split(".")[0]
    raw_header = base64.b64decode(jwt_header)
    json_header = json.loads(raw_header)

    return json_header.get("kid")


# validate that this jwt is coming from microsoft (for our tenant) along with the integrity and expiration time
def token_validate(jwt):
    # create jwt instance and get all daily public keys
    jwt_object = JWT()
    pub_keys = get_microsoft_pub_keys()

    # get kid from header in jwt
    real_kid = get_kid(jwt)

    # default username and dec_msg
    username = None
    dec_msg = ValidationMsg.NO_KEY_FOUND

    # check every public key looking for one used for signing this jwt
    for p_key in pub_keys.get('keys'):
        if p_key.get('kid') == real_kid:
            # assemble jwk
            jwk_dict = {
                    'kty': p_key['kty'],
                    'e': p_key['e'],
                    'n': p_key['n']
                }
            jwk = jwk_from_dict(jwk_dict)

            try:
                # perform decoding of the jwt while verifying integrity, authenticity and expiration
                dec_payload = jwt_object.decode(jwt, jwk, do_time_check=True)
                dec_msg = ValidationMsg.SUCCESS
                username = dec_payload['preferred_username']
            except:
                # an error occurred while validating the jwt
                dec_msg = ValidationMsg.FAILED

    return username, dec_msg


# main validation function
def main(jwt):
    username, result = token_validate(jwt)

    if result == ValidationMsg.SUCCESS:
        print(username)
    elif result == ValidationMsg.FAILED:
        # token validation failed, so stop program abruptly raising an exception
        raise Exception("JWT validation failed!")
    else: # result == ValidationMsg.NO_KEY_FOUND
        # no valid key was found (jwt MAY be valid but keys could have rotated)
        raise Exception("No public key that signed this JWT could be found!")

    return


# check and parse correct num of args
def get_args():
    if len(sys.argv) != 2:
        print(f"[+] Usage: {sys.argv[0]} <JWT to validate>")
        exit(127)

    jwt = sys.argv[1]
    return jwt


# only run when called as main program
if __name__ == "__main__":
    args = get_args()
    main(args)
