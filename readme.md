Create an application that will allow to submit forms with one or more files and view the forms later.

The page will have 
1. add form button that will open a add form dialog
1. Table of submitted forms that will show name and view button.

Add form dialog  will have :
1. Form name text input field
1. Add file button
1. Save Form button

When add file button will be clicked, it will show a new row with name(text field) and upload option.
User can add and upload multiple files this way.
Once form is saved, the dialog is closed.


When we click on one of the saved forms, we will get a dialog that will alow us to view the submited files for this form. (No need to have edit option)

You can implement it with Laravel + Vue or Native Js and use any libraries

Put the code on server of your choice and provide me a link to working env.

-----------------

## To Test

```
./artisan serve
```

## Dev

```
touch database/database.sqlite
./artisan migrate
```
