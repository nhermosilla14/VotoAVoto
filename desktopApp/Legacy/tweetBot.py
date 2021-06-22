#!/usr/bin/env python
import sys
import json
import os
import glob
import time
import twitter

path_to_votes = 'votes/decrypted/'

def main():
    if len(sys.argv) != 2:
        print("VoteApp-Counter v0.1\nUso: VotoApp-cli PATH_TO_CREDENTIALS_JSON\n\ ")
    else:
        count = 1
        c_blanco = c_nulo = c_apruebo = c_rechazo = 0
        with open(sys.argv[1]) as f:
            creds = json.load(f)

        api = twitter.Api(  consumer_key=creds["tw_consumer_key"],
                            consumer_secret=creds["tw_consumer_secret"],
                            access_token_key=creds["tw_access_token_key"],
                            access_token_secret=creds["tw_access_token_secret"])

        for filename in glob.glob(os.path.join(path_to_votes, '*.json')):
            with open(filename, 'r') as f:
                vote_contents = json.load(f)

            option = vote_contents["opt"]
            if not option: # if option == ""
                option = "blanco"
                c_blancos += 1
            elif option == "aprueborechazo":
                option = "nulo"
                c_nulo +=1
            elif option == "apruebo":
                c_apruebo += 1
            elif option == "rechazo":
                c_rechazo += 1
            else:
                pass
            message = vote_contents["msg"]
            if not message:
                tweet_body = "Voto #"+str(count)+": "+option+"."
            else:
                tweet_body = "Voto #"+str(count)+": "+option+", "+message+"."

            tweet_body = tweet_body + "\n\nGlobal:"
            tweet_body = tweet_body + "\nApruebo: "+str(c_apruebo)
            tweet_body = tweet_body + ", Rechazo: "+str(c_rechazo)
            tweet_body = tweet_body + "\nBlanco: "+str(c_blanco)
            tweet_body = tweet_body + ", Nulo: "+str(c_nulo)

            try:
                status = api.PostUpdate(tweet_body[:280])
            except UnicodeDecodeError:
                print("Your message could not be encoded.  Perhaps it contains non-ASCII characters? ")
                print("Try explicitly specifying the encoding with the --encoding flag")
                sys.exit(2)
            count = count+1
            time.sleep(10)

if __name__ == '__main__':
    main()
