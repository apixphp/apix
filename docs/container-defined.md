# Container Procedure
* Container means easy access to classes that in system. Container is defined for 'app' helper function and 'container' static class
* container static class contains getContainer method in src/app/config.php and config/app.php that in specific config of the every project
* Both of them are joined and only one container is obtained
* app helper function can access namespace and container classes



#### Container usage

```
public function () {
    //it checks user device
    return \Container::device()->isMobile();
}

```

#### App helper method usage

```
public function () {
    //it checks user device
    return app("device")->isMobile();
}

```


# Company specific container class
* src/app/config.php

# Project specific container class
* src/app/project_name/version/config/app.php
