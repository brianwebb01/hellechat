#!/bin/bash

service_name=app
nginx_container_name=nginx

reload_nginx() {
  docker exec $nginx_container_name /usr/sbin/nginx -s reload
}

# server health check
server_status() {
  # $1 = first func arg
  local port=$1
  statis=$(cgi-fcgi -bind -connect localhost:$port | head -n1 | cut -d " " -f2 | cut -d "/" -f1)

  # if status is not a status code (123), means we got an error not an http header
  # this could be a timeout message, connection refused error msg, and so on...
  if [[ $(echo ${statis}) != "PHP" ]]; then
    echo 503
  fi

  echo 200
}

update_server() {

  old_container_id=$(docker ps -f name=$service_name -q | tail -n1)

  # create a new instance of the server
  docker-compose up --build -d --no-deps --scale $service_name=2 --no-recreate $service_name
  new_container_id=$(docker ps -f name=$service_name -q | head -n1)

  if [[ -z $new_container_id ]]; then
    echo "ID NOT FOUND, QUIT !"
    exit
  fi
  new_container_port=$(docker port $new_container_id | cut -d " " -f3 | cut -d ":" -f2)

  if [[ -z $new_container_port ]]; then
    echo "PORT NOT FOUND, QUIT !"
    exit
  fi

  # sleep until server is up
  while [[ $(server_status $new_container_port) > 404 ]]; do
    echo "New instance is getting ready..."
    sleep 3
  done

  # ---- server is up ---

  # reload nginx, so it can recognize the new instance
  reload_nginx

  # remove old instance
  docker rm $old_container_id -f

  # reload ngnix, so it stops routing requests to the old instance
  reload_nginx

  echo "DONE !"
}

# call func
update_server