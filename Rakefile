task :deploy do |t|
  sh "git push origin master"
  sh "rsync -aP --exclude-from='rsync-exclude.txt' . $DAVE_REMOTE"
end

task :default => [:deploy]
