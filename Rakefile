task :deploy do |t|
  sh "git push origin master"
  sh "git push prod master"
end

task :default => [:deploy]
