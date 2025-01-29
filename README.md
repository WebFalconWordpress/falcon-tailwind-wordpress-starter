# Falcon Tailwind Wordpress Starter Enviroment

Purpose of this repo is to demonstrate and document recommended workflows for integrating WP Engine, Local, Git and Github in our Wordpress development cycle.  

URL of the WP Engine instance is: [https://falconstarter.wpengine.com/](https://falconstarter.wpengine.com/)





## Initial workflow (Fresh site creation)

When starting work on a new Wordpress website follow these steps: 
- Create new site on WP Engine.
- Create local instance by downloading it using Local: (more details [here](https://wpengine.com/support/local/)).
- Create new Github repo.
- Create new repository secret:
  - copy key value from 'opensourceteam' KP database (entry name is *WPE_SSHG_KEY*)
  - create new secret (find it on Github repo *-> Settings -> Secrets and variables -> Actions -> New repository secret*) named *WPE_SSHG_KEY*.
  - paste value from KP into *Secret* field and save
- Initialize git repo inside Wordpress root directory using: **git init**.
- Set default git branch to match Github conventions: **git checkout -b main**.
- Add our recommended .gitignore file to the repo (link [here](.gitignore)). This file is a good starting point but it can be edited when necessary.
- Add our Github Action yaml file to the repo (link [here](.github/workflows/ghaction-wpengine-prod.yml)). The path needs to be the same as in this repo (inside the .github/workflows/ directory) so that Github can register it. There are couple of variables inside this file but the only one that needs to be adjusted for the new repo is the *WPE_ENV* and it's value should match the name of new site on WP Engine. 
- Connect local git repo with Github remote using **git remote add origin >github-repo-url<** (more details [here](https://docs.github.com/en/get-started/importing-your-projects-to-github/importing-source-code-to-github/adding-locally-hosted-code-to-github)).  

<br />

![flow image](/wp-flow.png)  

<br />


## Creating new Local instance from an existing WP Engine site and Github repo

- Use Local to connect to WP Engine and pull the site.
- Use git to get the repo from Github to the Wordpress root directory using these commands:
  - These commands should be run from the Wordpress root directory (for Local sites this is *<project dir>/app/public*) and since there are already files there it's a slightly different procedure than starting with a fresh site.
  - Run **git init** to initialize the repo.
  - Run **git remote add origin >github-repo-url<** to connect with the Github repo.
  - Run **git fetch** to get existing git data.
  - Run **git checkout -t origin/main -f** to set upstream branch.



## Recommended practices

### Database workflow

- Database shoud usually follow this flow *Production -> Staging -> Local*
- Recommended tool for this is Local and usually we would use feature *Pull from WP Engine - Include Database* 
- Breaking this flow (e.g. pushing database from Local to Staging) could make sense in some cases during initial development stages and if there is only one developer working on the project. When there are multiple people working on the project or the project already has an active Production instance it's best to stick to the recommended flows.

![flow image](/dev_staging_prod.webp)

### Custom code workflow

- Custom code should always follow this flow *Local -> Staging ->  Production*
- We use Local instance to make and test changes, then commit them via Git to the Github repo and this then automatically triggers code changes to be deployed to the WP Engine instance.

### Add new plugin workflow

- This workflow is similar to the previous one. First we add and activate new plugin on the Local instance, then we use git to commit and push new files (this will automatically deploy them to the WP Engine instance), and finally we activate it on the WP Engine instance.


### Uploads workflow

- We don't track uploads directory with git (this is set up inside recommended .gitignore file). 
- Recommended flow would be to first upload the media files on WP Engine instance, and then we use Local to download the files (using feature *Pull from WP Engine - Include Database*)
- Alternative flow would be to add new media files on Local instance first and then use SFTP or Local to push them to the WP Engine instance but this is likely to cause issues if we are using WP dashboard to upload files because this means that we now have references (Media Library) in our local database and we want to avoid pushing database in that direction. Workaround would be to use *WP-CLI* (the **wp media import** command) or some plugin dedicated for this purpose but unless we are dealing with bulk upload it's probably easier to stick to the recommended flow.


### Add new post, page etc workflow

- Our recommended flow would be to create them first on WP Engine instance and then use Local to pull down changes (using feature *Pull from WP Engine - Include Database*). 
- This flow simply matches our database flow for obvious reasons.


### Recommended .gitignore file

- In our recommended .gitignore file we define which parts of the site should be tracked in git and which of them should be excluded.
- In general we want to include our custom code (theme, plugins) and exclude Wordpress core files, media files, Wordpress config and WP Engine specific files.
- It's important to note that WP Engine already manages Wordpress core files by default and doesn't allow for them to be modified (more details [here](https://wpengine.com/support/modified-core/))


### Recommended Github actions

- Example Github action given in this repo enables deployment of a Wordpress site from the Github repo to the WP engine instace.
- This example repo assumes only one Environment on the WP engine site (production) but if there are multiple environments or some additional instances outside WP Engine we should then have seperate action files for each instance/environment.
- Explaining how Github actions work is beyond the scope of this doc but a good starting point with more details can be found [here](https://docs.github.com/en/actions/learn-github-actions/understanding-github-actions)


### Recommended links

- [https://wpengine.com/support/local/](https://wpengine.com/support/local/)
- [https://docs.github.com/en/get-started/importing-your-projects-to-github/importing-source-code-to-github/adding-locally-hosted-code-to-github](https://docs.github.com/en/get-started/importing-your-projects-to-github/importing-source-code-to-github/adding-locally-hosted-code-to-github)
- [https://wpengine.com/support/modified-core/](https://wpengine.com/support/modified-core/)
- [https://docs.github.com/en/actions/learn-github-actions/understanding-github-actions](https://docs.github.com/en/actions/learn-github-actions/understanding-github-actions)
- [https://wpengine.com/support/git/](https://wpengine.com/support/git/)
- [https://wpengine.com/support/development-workflow-best-practices/](https://wpengine.com/support/development-workflow-best-practices/)
- [https://docs.vasdomen.com/display/WPEPIKAD/Epikad+WordPress+-+Dashboard](https://docs.vasdomen.com/display/WPEPIKAD/Epikad+WordPress+-+Dashboard)


