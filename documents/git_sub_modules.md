# Using git submodules

## Add sub module:

```
git submodule add <uri to sub module repo> <folder_name>
```
  
Then commit and push
  
## Cloning with sub modules

```
git clone <uri>
git submodule init
```
  
Or:

```
git clone --recurse-submodules <uri>
```
  
## Updating sub module
  
Go into the submodule folder and do a git pull

## Updating the main project

```
git pull
git submodule update --init --recursive
```
