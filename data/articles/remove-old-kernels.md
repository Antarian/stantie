---
title: >-
   Remove old kernels in Ubuntu 14.04
preview: >-
   This is step-by-step tutorial how to manage code deployment from GitHub to AWS for your Ionic 4 AngularJS PWA. For this tutorial you wil need AWS and GitHub accounts.
slug: 'remove-old-kernels-in-ubuntu-14-04'
categorySlug: 'quick-wins'
seriesSlug: null
seriesPart: null
archived: true
author: 'Peter Labos'
published: '12th Mar 2016'
---
# Remove old kernels in Ubuntu 14.04

From time to time Ubuntu 14.04 fails to update because of insufficient space. It will show you something like this message:
```text
Not enough free disk space
The upgrade needs a total of 101 M free space on disk '/boot'.
Please free at least an additional 24.9 M of disk space on '/boot'.
Empty your trash and remove temporary packages of former installations using 'sudo apt-get clean'.
```

If `sudo apt-get clean` won’t help (which is almost never), there is only few little steps to free this space and get Ubuntu updates.

1. To know which version you currently running use this little code in command line:
    ```shell
    uname -r
    ```
    or for more complete report
    ```shell
    uname -a
    ```
    For me, it is showing version `3.19.0-51-generic` for you, it can be a different version. It’s definitely not a good idea to delete this version. Remember or write this version name somewhere.

2. Find all kernel versions in the system:
    ```shell
    dpkg --list | grep linux-image
    ```
    This will print out the list of all kernel versions, currently stored or used in the system. Important are `linux-image-x.xx.x-xx` items in list.

3. Now when you know what version you are currently running, and all versions in system, you can remove older versions. Starting from oldest and not deleting current. For example:
    ```shell
    sudo apt-get purge linux-image-3.xx.x-xx-generic
    ```
    where `3.xx.x-xx` you will replace by your version. This command will ask you for admin password (`sudo` part of it).

If there are more than two versions, I personally leave 2 newest versions untouched. This is always enough for me to get rid of `Not enough free disk space` error at updating.
