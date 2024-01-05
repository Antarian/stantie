---
title:  >-
  Ionic 4/Angular deployment to AWS
preview: >-
  This is step-by-step tutorial how to manage code deployment from GitHub to AWS for your Ionic 4 AngularJS PWA. For this tutorial you wil need AWS and GitHub accounts.
slug: 'ionic-4-angular-deployment-to-aws'
categorySlug: 'quick-wins'
seriesSlug: null
seriesPart: null
archived: true
author: 'Peter Labos'
published: '12th Aug 2019'
---
# Ionic 4/Angular deployment to AWS

This is a step-by-step tutorial how to manage code deployment from GitHub to AWS for your Ionic 4 AngularJS PWA.

For this tutorial, you will need AWS and GitHub accounts.

## Step #1 - Required project changes

We will use the new sample Ionic 4 app. But these steps are applicable to any Ionic 4 project.

Install Ionic 4 app
```shell
npm install -g ionic
ionic start ionicawscicd tabs
```

Run the app
```shell
cd ionicawscicd
ionic serve
```

This command will show you where your app is running. Usually it is [http://localhost:8100](http://localhost:8100)

In a root folder of your Ionic 4 project put the file `buildspec.yml` defined by rules of AWS CodeBuild with this content:
```yaml
version: 0.2

phases:
  install:
    runtime-versions:
      nodejs: 12
    pre_build:
      commands:
        - yarn install
    build:
      commands:
        - npm run build:prod
    artifacts:
      files:
        - '**/*'
    base-directory: www
```

Explanation of phases
- **install** - NodeJS as runtime environment, as we are building Ionic 4/Angular 8 project
- **pre-build** - yarn install to get all required dependencies for build
- **build** - npm running build command, which is pointing to our `package.json` file

Explanation of artifacts
- **files** - types of files the build pipeline should use
- **base-directory** - directory where build files can be found

We will also alter `package.json` file to contain changes relevant for build
```json
{
  "scripts": {
    ...,
    "build:prod": "./node_modules/.bin/ionic build --prod"
  },
    ...,
  "dependencies": {
    ...
    "ionic": "^4.0.0",
    ...
  }
}
```

Explanation: `build:prod` is pointing to local Ionic CLI, which is added to `dependencies`.

## Step #2 - Creating GitHub project
Log to your GitHub account [https://github.com/login[(https://github.com/login) and create a new public repository named `ionicawscicd`.

[image mising](/)

[image mising](/)

From result save SSH or HTTPS link:

[image mising](/)

You can now go to your project folder on your disk and setup it for git.
```shell
git init
git add .
git commit -m"project first commit"
```

This step will be a little different for everyone. It depends on the name of your repository, and if you already set up an SSH key for GitHub. You can use the SSH link of the repository like me or https.
```shell
git remote add origin git@github.com:Antarian/ionicawscicd.git
git push -u origin master
```

Now if you refresh GitHub page (mine is [https://github.com/Antarian/ionicawscicd](https://github.com/Antarian/ionicawscicd)) you should see Ionic project codebase

[image mising](/)

## Step #3 - Creating AWS S3 bucket

Go to your AWS console account and access Amazon S3 home page. [Amazon S3](https://console.aws.amazon.com/s3/home) and start creating new bucket by clicking button `+ Create bucket`

[image mising](/)

Enter DNS compliant bucket name and select region. I will name it `ionicawscicd` and my closest region is EU (London).

[image mising](/)

Let all other steps on default for now. We may change them later.

Your bucket should be now created.

[image mising](/)

and we can continue to next step.

## Step #4 - Creating CodePipeline

On the page [https://console.aws.amazon.com/codesuite/codepipeline/pipelines](https://console.aws.amazon.com/codesuite/codepipeline/pipelines) change region in the right top corner to the one where you are deploying app. Mine is `EU London`.

[image mising](/)

Next click the button `Create pipeline`.

[image mising](/)

I named the pipeline `ionicawscicd-pipeline` and click button `next`.

[image mising](/)

As a source select `GitHub` and then click connect button. If you were logout, then login to GitHub again in the window provided by AWS CodePipeline. Select your `ionicawscicd` repository and branch `master`.

[image mising](/)

As a build provider select AWS CodeBuild and click `Create project` button.

Name it `ionicawscicd-build` Select `Managed image`, `Operating system Ubuntu`, runtime `Standard`,

'Image standard:3.0' this is the image version and always uses the latest. 'Environment type' is 'Linux'.

Check 'New service role' and using of 'BuildSpec' file.

[image mising](/)

[image mising](/)

[image mising](/)

[image mising](/)

 Select Amazon S3 and our earlier created S3 bucket.
[image mising](/)

## Step #5 - Publishing website with CloudFront

On [https://console.aws.amazon.com/cloudfront/home](https://console.aws.amazon.com/cloudfront/home) we will create new distribution.

[image mising](/)

Select the web

[image mising](/)

As 'origin domain name' select our 'S3 bucket' domain. Should be something similar to `ionicawscicd.s3.amazonaws.com`.
