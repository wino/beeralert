# beeralert
This script will scrape the website for a bar's draft listing, determine if any new beers have been added, and create an RSS feed entry for the new beers. The feed can be read by IFTTT and used to send an email alert. 

allbeerfeed.php is the main script that will run all the bars. TBCfeed.php is an example of running it just for a single bar.

The files in the 'prev' dir must be writeable by apache to update the current list of beers, which is used for a diff with future runs.

Currently supported bars: Tasty Beverage

Previously supported and possibly again in the future: State of Beer, Trophy Morgan/Maywood
