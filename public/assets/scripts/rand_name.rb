#!/usr/bin/env ruby
# rand_name
# returns (a) random name(s) using the name generator from gemwarrior
# (and copies to clipboard)

require_relative "lib/name_generator"

reps = ARGV[0] ? ARGV[0].to_i : 1
names = []

1.upto(reps) do
  name = NameGenerator.new('name', 'fantasy').generate_name
  names << name
  system("echo #{name}")
end

def pbcopy(arg)
  IO.popen('pbcopy', 'w') { |io| io.puts arg }
end

# copy to macOS clipboard if appropriate
pbcopy names if RUBY_PLATFORM.include? 'darwin'