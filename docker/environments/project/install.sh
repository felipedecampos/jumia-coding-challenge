#!/usr/bin/env bash

if source ././.env; then
    if [ "$NETWORK_NAME" = "{NETWORK_NAME}" ] || [ "$NETWORK_IP" = "{NETWORK_IP}" ]; then
        echo -e "There isn't any Network set up yet! \n"
        read -p ''
        exit 0
    fi
else
    clear
    echo -e "There isn't any Network set up yet! \n"
    read -p ''
    exit 0
fi

read -p "Project name [jumia-coding-challenge]: " projectname
if [ -z "$projectname" ]; then
    projectname="jumia-coding-challenge"
fi

r_projectname="$(echo $projectname | sed -e 's/\(.*\)/\L\1/' | awk '{$1=$1};1' | sed -e 's/[[:space:]]\+/\-/g')"

echo -e "\n\e[32m$r_projectname\e[0m\n"

read -p "Project PATH [/home/$USER/Workspace/$r_projectname]: " projectpath
if [[ -z "$projectpath" ]]; then
    projectpath="/home/$USER/Workspace/$r_projectname"
fi

r_projectpath=${projectpath////\\/}

networkipbase=$(cut -d '.' -f 1,2,3 <<<"$NETWORK_IP")

read -p "App url [$r_projectname.local]: " appurl
if [ -z "$appurl" ]; then
    appurl="$r_projectname.local"
fi

nginxhost="$networkipbase.2"
phphost="$networkipbase.3"
dbconnection="sqlite"
dbhost="127.0.0.1"
dbname="/var/www/jumia/database/sample.db"
dbport="3306"

r_dockerprojectpath=${DOCKER_PROJECT_PATH////\\/}
r_dbname=${dbname////\\/}

printf "\n"

yes | cp -i $DOCKER_PROJECT_PATH/environments/project/.env.example $DOCKER_PROJECT_PATH/environments/project/.env

echo -e "\n"

sed -i "s/{PROJECT_NAME}/$r_projectname/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{PROJECT_PATH}/$r_projectpath/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{APP_URL}/$appurl/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{NGINX_HOST}/$nginxhost/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{PHP_HOST}/$phphost/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{POSTGRES_HOST}/$dbhost/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{POSTGRES_DB}/$r_dbname/g" $DOCKER_PROJECT_PATH/environments/project/.env
sed -i "s/{POSTGRES_PORT}/$dbport/g" $DOCKER_PROJECT_PATH/environments/project/.env

yes | cp -i $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml.example $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml

echo -e "\n"

sed -i "s/\${PROJECT_NAME}/$projectname/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${PROJECT_PATH}/$r_projectpath/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${APP_URL}/$appurl/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${NGINX_HOST}/$nginxhost/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${PHP_HOST}/$phphost/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${NETWORK_NAME}/$NETWORK_NAME/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${NETWORK_IP}/$NETWORK_IP/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml
sed -i "s/\${DOCKER_PROJECT_PATH}/$r_dockerprojectpath/g" $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml

yes | cp -i $DOCKER_PROJECT_PATH/environments/project/nginx/nginx.conf.example $DOCKER_PROJECT_PATH/environments/project/nginx/nginx.conf

echo -e "\n"

sed -i "s/{APP_URL}/$appurl/g" $DOCKER_PROJECT_PATH/environments/project/nginx/nginx.conf
sed -i "s/{PROJECT_PATH}/$projectname/g" $DOCKER_PROJECT_PATH/environments/project/nginx/nginx.conf

yes | cp -i $DOCKER_PROJECT_PATH/environments/project/php-fpm/overrides.ini.example $DOCKER_PROJECT_PATH/environments/project/php-fpm/overrides.ini

echo -e "\n"

sed -i "s/\${NGINX_HOST}/$nginxhost/g" $DOCKER_PROJECT_PATH/environments/project/php-fpm/overrides.ini

read -p "Are you sure you want to install the project [$r_projectname] y/n? [y]: " yn
if [[ "$yn" != "n" ]]; then

    echo -e "\n"

    yes | cp -i $projectpath/.env.example $projectpath/.env

    sed -i "s/APP_NAME\=Laravel/APP_NAME\=$r_projectname/g" $projectpath/.env
    sed -i "s/APP_URL\=http\:\/\/localhost/APP_URL\=http\:\/\/$appurl/g" $projectpath/.env
    sed -i "s/DB_CONNECTION\=mysql/DB_CONNECTION\=$dbconnection/g" $projectpath/.env
    sed -i "s/DB_HOST\=127\.0\.0\.1/DB_HOST\=$dbhost/g" $projectpath/.env
    sed -i "s/DB_PORT\=3306/DB_PORT\=${dbport%?}/g" $projectpath/.env
    sed -i "s/DB_DATABASE\=homestead/DB_DATABASE\=$r_dbname/g" $projectpath/.env

    docker-compose -f $DOCKER_PROJECT_PATH/environments/project/docker-compose.yml --project-name $r_projectname up -d --force-recreate --build --remove-orphans

    sleep 15

    docker exec --user docker $r_projectname-php-fpm /bin/bash -c "cd $r_projectname && composer install"

    docker exec --user docker $r_projectname-php-fpm /bin/bash -c "cd $r_projectname && php artisan key:generate"

    chmod 777 $(find ../storage/ -not -name ".gitignore")
    chmod 777 $(find ../bootstrap/cache/ -not -name ".gitignore")

    docker exec --user docker $r_projectname-php-fpm /bin/bash -c "cd $r_projectname && npm install"

    read -p "Do you want to add the project host on your /etc/hosts file? (warning: This command needs the sudo privilege) y/n? [n]: " yn
    if [[ "$yn" = "y" ]]; then
        etchosts=/etc/hosts
        hostsline="$nginxhost\t$appurl"

        if [ -n "$(grep $appurl $etchosts)" ]
            then
                echo -e "$appurl already exists in $etchosts: $(grep $appurl $etchosts)\n"
            else
                echo -e "Adding $appurl to your $etchosts\n";
                sudo -- sh -c -e "echo '$hostsline' >> $etchosts";

                if [ -n "$(grep $appurl $etchosts)" ]
                    then
                        echo -e "$appurl was added succesfully in $etchosts \n $(grep $appurl $etchosts)";
                    else
                        echo -e "Failed to Add $appurl in $etchosts, Try again!\n";
                fi
        fi
    else
        echo -e "\n\e[33mPlease, put in your /etc/hosts file the host of this project: \n\n$nginxhost\t$appurl\e[0m\n"
    fi

    docker exec --user docker $r_projectname-php-fpm /bin/bash -c "cd $r_projectname && npm run dev"

    echo -e "\nProject \e[32m$r_projectname\e[0m was successfully installed \n"

    echo -e "Click the link below to access the project: \n"

    echo -e " \e[44mhttp://$appurl\e[0m\n"

else

    echo -e "\n The project was successfully set up \e[32m$r_projectname\e[0m but it was not installed\n"

fi

read -p ''