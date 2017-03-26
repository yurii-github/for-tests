test 8
========

A Symfony project created on March 22, 2017, 5:58 pm.


Contains independent bundle `Yurii\OroBundle` that

- replaces Symfony Router during CompilerPass with ours
- adds to our Router custom loader (as injected service) that adds additional routes to collection
- sets translation domain to 'example' via decorator

To run tests, execure in app root dir  
```phpunit src/Yurii/OroBundle/Tests/```

works on hosts
- example.com
- example.com/fr
- example.fr

![Image](https://raw.githubusercontent.com/yurii-github/test8/master/page.png)

