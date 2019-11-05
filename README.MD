
## The Task

 Our advertising platform promotes mobile applications.  
 It contains a campaign for each such application
 our publishers bring users that install and then use these applications
 the platform is reported about the install event and other application usage events of these users
 for example "app_open", "registration" and "purchase" events
 this stream of events is saved in a database

 To achieve quality goals we optimize campaigns by blacklisting publishers who do not qualify  the campaign's expections

 For example, a campaign may expect the number of "purchase" events a publisher brings to be equal or
 greater than 10% of the number of installs that publishers brought,
 or else the publisher should be blacklisted on that campaign

 To maintain these publisher blacklists we have a job process (`OptimizationJob`) that runs every hour

 Campaign objects contain an `optimizationProps` object that includes the following properties:
 * `sourceEvent` and `measuredEvent` - in the above example sourceEvent would be "install" and measuredEvent
   would be "purchase"
 * threshold - the minimum of occurrences of sourceEvent, if a publisher has less sourceEvents that the threshold ,
   then she should not be blacklisted
 * ratioThreshold - the minimum ratio of sourceEvent occurrences to measuredEvent occurrences

 Event objects contain their type, the campaignId and publisherId

 Below is the begining of the implementation of the OptimizationJob class,
 
* A. complete the implementation maintaining campaigns' publishers blacklists  
    Keep in mind that blacklisted publishers can only be removed from the blacklist if they cross the ratio
* B. make sure publishers are notified with an email whenever they are added or removed from a campaign's blacklist  
    Please do not implement the email mechanism - we assume you know how to send an email


## Dev

```
./vendor/bin/php-cs-fixer fix
```
