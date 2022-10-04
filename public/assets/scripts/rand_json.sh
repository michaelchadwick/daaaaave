#!/usr/bin/env bash

# https://gist.github.com/eiri/3d6e47b704b6b65f55474c0ada208db2
# prereq `brew install coreutils jo`
# modified slightly by me

seq_formatted() {
  printf "%04d" "$1"
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

defcount=1
limit=${1:-$defcount}
items=()

for i in $(seq "${1:-$limit}")
do
  items+=("$(jo -- seq="$i" -s count="$(seq_formatted "$i")" integer="$(random_integer 1000)" float="$(random_float 1000)" string="$(random_string 16)" hex="$(random_hex 16)" uuid="$(randon_uuid)" bool@"$(random_boolean)" word="$(random_word)" name="$(random_name)")")
done

jo items="$(jo -a "${items[@]}")"
