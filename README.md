# TrackYourClimb [![Build Status](https://travis-ci.org/shimizust/trackyourclimb.svg)](https://travis-ci.org/shimizust/trackyourclimb)

## Quick Summary ##

This repository contains the source code for www.trackyourclimb.com, a web app that helps climbers keep track of their climbs and analyze their progress. This project is built with PHP, MySQL, Javascript, CSS, and HTML. 

We welcome community contributions, and we've tried to make it very easy to get started. 

## How do I get set up? ##

(Windows instructions)

#### Set up Server and Repo
1. Download and install the latest version of XAMPP ([https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)), which is a free software package consisting of a web server, MySQL database, and PHP. The installation directory (e.g. C:\xampp) will be referred to as `${XAMPP}`)
2. From the command prompt, navigate to `${XAMPP}\htdocs`
3. Clone the repo. In the command prompt, enter:
	1. `git clone https://github.com/shimizust/trackyourclimb.git trackyourclimb`
	2. Note: Replace "trackyourclimb" with any name you want for your root folder. The root of the local git repo will be referred to as `${REPO}`
	
#### Set up database
1. Start the XAMPP control panel (`${XAMPP}\xampp-control.exe`)
2. From the control panel, start Apache and MySQL
3. Click "Admin" for MySQL or navigate to `localhost/phpmyadmin` in your browser
4. Click "New" on the left
5. Enter a name for the database (e.g. "trackyourclimb_db") and choose the collation to be `utf8_unicode_ci`. Click Create.
6. Import the database schema by clicking "Import" from the top menu bar and choosing `${REPO}\database_init\trackyourclimb_db_schema.sql`.

#### Set up the property files and directories
1. From the local git repo, make a copy of `${REPO}\siteproperties-TEMPLATE.ini` and rename to `${REPO}\siteproperties.ini`
2. In `siteproperties.ini`, fill in the following parameters:
	1. `db_name = "trackyourclimb_db"` [or whatever you named the database]
	2. `db_username = "root"` [or another username to access the database, "root" is the default]
	3. `db_password = ""` [or whatever password you set, blank "" is the default]
	4. You can leave the Mailchimp details blank for now.
5. Create the following directory `${REPO}\userimages`. This is used to store user profile pictures.

#### Install PHP packages
1. Follow these instructions to install Composer, a PHP package manager (see Installation - Windows: Using the Installer): [https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md)
2. Navigate to `${REPO}` from the command prompt. Enter `composer install` to install PHP packages, which will reside in the `${REPO}\vendor` folder.

#### Install Client-Side (JS, CSS) packages
1. Download and install Node.js ([https://nodejs.org/](https://nodejs.org/))
2. Use the Node Package Manager (npm), to install Bower (client-side package manager)
	1. Open a command prompt anywhere, and enter: `npm install -g bower`
3. From the command prompt, navigate to `${REPO}` and run `bower install`, which will install dependencies in the `${REPO}\bower_components` folder.

#### Test it out
1. In your browser, navigate to `localhost/trackyourclimb` (use whatever you named the root folder). You should see the trackyourclimb website with no data.
2. This is a local version of the website you can play around with and test out your changes, although it requires internet access for some libraries delivered from CDNs.

#### Running Tests ####
1. Install PHPUnit using these instructions: [https://phpunit.de/getting-started.html](https://phpunit.de/getting-started.html)
2. All tests are contained under /tests/ with the filename format: *Test.php
3. From project root, run the command `phpunit tests` to determine if tests pass.

#### Changing CSS ####

The project uses SCSS, which adds better functionality to the existing CSS syntax. All of the site's custom CSS is written in `${REPO}\css\scss\mycss.scss`.


## Contribution guidelines ##

1. If you have a bug fix, feature, design change, etc., post an issue in the Github repository.
2. If we decide that this change makes sense, someone will be assigned the fix.
3. After forking the repository and implementing the change, issue a pull request. If approved, the change will be merged. Shortly thereafter, the website will be manually synced with the Github repo.

Here are some areas of contribution:
- bug fixes
- refactoring code
- making the design better
- adding new features
- improving the build process
- writing tests
- documentation

## Who do I talk to? ##

* Repo owner: Steven Shimizu (shimizust@gmail.com)