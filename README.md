AddCountriesCommand for Symfony 4+ (didn't check on lower versions, but should be fine too)
=========
Easy to integrate Console command to seed countries in to your database:
-----
```
bin/console app:command:add-countries
``` 
Don't forget to update your entities before using this command:
---
 - `bin/console d:m:diff`
 - `bin/console d:m:m`

If you don't use SonataMediaBundle:
 - edit `Entity\Country.php` and remove Media type

Please star this repo, fork and write me if some problems are detected! Thank you!
