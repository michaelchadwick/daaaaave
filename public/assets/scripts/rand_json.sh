#!/bin/bash

# https://gist.github.com/eiri/3d6e47b704b6b65f55474c0ada208db2
# prereq `brew install coreutils jo`
# modified slightly by me

defcount=1

display_usage() {
  echo -e "Generate NUMBER of random json objects."
  printf "  Usage: %s [NUMBER] (DEFAULT: %s)\n\n" "$(basename "$0")" $defcount
}

random_integer() {
  shuf -i 1-"$1" -n 1
}

random_float() {
  echo "$((RANDOM%($1))).$((RANDOM%999))"
}

random_hex () {
  openssl rand -hex "$1"
}

randon_uuid() {
  uuidgen | tr '[:upper:]' '[:lower:]'
}

random_string () {
  base64 /dev/urandom | tr -d '\+\/' | dd bs="$1" count=1 2>/dev/null
}

random_boolean() {
  shuf -i 0-1 -n 1
}

random_word() {
  shuf -n 1 /usr/share/dict/words | tr -d '\n'
}

random_name() {
  shuf -n 1 /usr/share/dict/propernames | tr -d '\n'
}

if [[ ( $@ == "--help") ||  $@ == "-h" ]]
then
  display_usage
  exit 0
fi


for i in $(seq "${1:-$defcount}")
do
  jo \
    seq="$i" \
    count="$(printf "%04d" "$i")" \
    integer="$(random_integer 1000)" \
    float="$(random_float 1000)" \
    string="$(random_string 16)" \
    hex="$(random_hex 16)" \
    uuid="$(randon_uuid)" \
    bool@"$(random_boolean)" \
    word="$(random_word)" \
    name="$(random_name)"
done