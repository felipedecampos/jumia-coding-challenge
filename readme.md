# Coding challenge - List phone numbers and categorize by country

## ** Introduction ** 
[Link to my introduction](https://github.com/felipedecampos/jumia-coding-challenge/tree/master/docs/INTRODUCTION.md)

## ** Test specification ** 
[Link to the test specification](https://github.com/felipedecampos/jumia-coding-challenge/tree/master/docs/jumia-recruitment-exercise.pdf)

## ** What has been done **

First you will need to install the application environment with docker-compose

I provide an easy installation with a script in shell script:

[Go to How to install the project environment](https://github.com/felipedecampos/jumia-coding-challenge#-how-to-install-the-project-environment-)

The script didn't work?

[Go to How to manually install the project environment](https://github.com/felipedecampos/jumia-coding-challenge#the-script-didnt-work)

Then you will be taken to the index page and you will see the list of customers' phone numbers classified by country and results limited to 10 items per page.

After paginating, you will see the next 10 items until you reach the end of the list.

After filtering by country, you will see the list of customers' phone numbers according to the selected country.

After filtering by state, you will see the list of customers' phone numbers according to the selected state.

## ** PHP Standards Recommendations **

To validate the code for consistency with a coding standard go to the **project folder** and run the commands:

**PSR-1**
```shell
$ vendor/bin/phpcs --standard=PSR1 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/bootstrap/cache/* .
```

**PSR-2**

```shell
$ vendor/bin/phpcs --standard=PSR2 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/bootstrap/cache/* .
```

**PSR-12**

```shell
$ vendor/bin/phpcs --standard=PSR12 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/bootstrap/cache/* .
```

## ** How to install the project environment **

#### Linux:

#### requirements:

- **debian** version **9 (Stretch)** OR **Mint** version **18.2 (Sonya)**
- **docker** version **17.05.0-ce**
- **docker-compose** version **1.19.0**
- **git** version **2.7.4**

To know your Linux version run:

```shell
$ cat /etc/os-release
```

To know your docker version run:

```shell
$ docker -v
```

To know your docker-composer version run:

```shell
$ docker-compose -v
```

To know your git version run:

```shell
$ git --version
```

**Clone the project**

```shell
$ git clone https://github.com/felipedecampos/jumia-coding-challenge.git
```

In the **project folder** run:  

```shell
$ cd docker && ./run.sh
```

**Enter the options bellow in the menu:**

When asked to "Type the service number you want to use:", enter option: **1- Set up new Network** 
> 1

  - Type the network name \[example: jumia\]: 
    > jumia

  - Type an IP for the network: 
    > 180.12.0.0

You should see: **Network jumia successfully set up**

Press Enter to go back to the menu

When asked to "Type the service number you want to use:", enter option: **2- Show current Network**
> 2

You should see: **Network set up: jumia > 180.12.0.0**

Press Enter to go back to the menu

When asked to "Type the service number you want to use:", enter option: **3- Install project**
> 3

**Note: If you want to insert the \[default value\], just press the Enter button**

  - Project name \[jumia-coding-challenge\]: 
    > jumia-coding-challenge 
  - Project PATH \[/home/USER/Workspace/jumia-coding-challenge\]: 
    > /home/$USER/Workspace/jumia-coding-challenge 
  - App url \[jumia-coding-challenge.local\]: 
    > jumia-coding-challenge.local
  - Are you sure you want to install the project \[jumia-coding-challenge\] y/n? \[y\]:
    > y
  - Do you want to add the project host on your /etc/hosts file? \(warning: This command needs the sudo privilege\) y\/n? \[n\]:
    > y

**Click the link below to access the project:**

[http://jumia-coding-challenge.local](http://jumia-coding-challenge.local)

##### Note: 

The installer helper will install the project environment with docker-compose

#### Windows and Macbook:

All tests were made in Debian 9 and Mint 18.2, I can't guarantee it will work on other operating systems

#### The script didn't work?

**Follow the steps below to install the project manually:**

In the **project folder** run:

```shell
yes | cp -i docker/.env.example docker/.env
yes | cp -i docker/environments/project/.env.example docker/environments/project/.env
yes | cp -i docker/environments/project/docker-compose.yml.example docker/environments/project/docker-compose.yml
yes | cp -i docker/environments/project/nginx/nginx.conf.example docker/environments/project/nginx/nginx.conf
yes | cp -i docker/environments/project/php-fpm/overrides.ini.example docker/environments/project/php-fpm/overrides.ini
yes | cp -i .env.example .env
```

Please, fill the variables of the files created above, follow the examples bellow:

**Note: Make sure to replace all variables defined with curly brackets {VARIABLE}**

**docker/.env**:

- replace the {NETWORK_NAME} variable with:
    > jumia
- replace the {NETWORK_IP} variable with:
    > 180.12.0.0
- replace the {DOCKER_PROJECT_PATH} variable with:
    > {PROJECT_FOLDER}/docker

**docker/environments/project/.env**:

- Replace the {PROJECT_NAME} variable with:
    > jumia-coding-challenge
- Replace the {PROJECT_PATH} variable with:
    > /home/{USER}/Workspace/jumia-coding-challenge
- Replace the {APP_URL} variable with:
    > jumia-coding-challenge.local
- Replace the {NGINX_HOST} variable with:
    > 180.12.0.2
- Replace the {PHP_HOST} variable with:
    > 180.12.0.3
- Replace the {POSTGRES_HOST} variable with:
    > 127.0.0.1
- Replace the {POSTGRES_DB} variable with:
    > /var/www/jumia/database/sample.db
- Replace the {POSTGRES_PORT} variable with:
    > 3306

**docker/environments/project/nginx/nginx.conf**:

- Replace the {APP_URL} variable with:
    > jumia-coding-challenge.local
- Replace the {PROJECT_PATH} variable with:
    > jumia-coding-challenge

**docker/environments/project/php-fpm/overrides.ini**:

- Replace the ${NGINX_HOST} variable with:
    > 180.12.0.2

**.env**:

- Fill the env file with the data bellow:
    > APP_NAME=jumia-coding-challenge
    
    > APP_URL=http://jumia-coding-challenge.local
    
    > DB_CONNECTION=sqlite
    
    > DB_HOST=127.0.0.1
    
    > DB_PORT=3306
    
    > DB_DATABASE=/var/www/jumia-coding-challenge/database/sample.db

In the **project folder** run:

```shell
docker-compose -f docker/environments/project/docker-compose.yml --project-name "jumia-coding-challenge" up -d --force-recreate --build --remove-orphans
docker exec --user docker jumia-coding-challenge-php-fpm /bin/bash -c "cd jumia-coding-challenge && composer install"
docker exec --user docker jumia-coding-challenge-php-fpm /bin/bash -c "cd jumia-coding-challenge && php artisan key:generate"
chmod 777 $(find ../storage/ -not -name ".gitignore")
chmod 777 $(find ../bootstrap/cache/ -not -name ".gitignore")
docker exec --user docker jumia-coding-challenge-php-fpm /bin/bash -c "cd jumia-coding-challenge && npm install"
docker exec --user docker jumia-coding-challenge-php-fpm /bin/bash -c "cd jumia-coding-challenge && npm run dev"
sudo -- sh -c -e "echo '180.12.0.2\tjumia-coding-challenge.local' >> /etc/hosts";
```

## ** How to uninstall the project environment **

In the **project folder** run:

```shell
$ cd docker && ./run.sh
```

**Enter the options bellow in the menu:**

When asked to "Type the service number you want to use:", enter option: **5- Uninstall project** 
> 5

When asked to "Are you sure you want to uninstall the project PROJECT-NAME (notice: There is no way to undo this) y/n? [n]:", enter option: **y**
> y

When asked to "Do you want to remove the project host on your /etc/hosts file? (warning: This command needs the sudo privilege) y/n? [n]:", enter option: **y**
> y

When asked to "Do you want to remove the project repository (notice: There is no way to undo this) y/n? [n]:", enter option: **y**
> y
