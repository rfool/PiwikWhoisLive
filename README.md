PiwikWhoisLive
==============

PiwikWhoisLive

http://dev.piwik.org/trac/ticket/1317

A Plugin for Piwik.


Similar to the Live plugin, but much simpler. Provides one widget, which:

    displays list of current visits
    with IP, Provider, Country/City, OS/Resolution, Browser, Referer/Keywords, Last Action Time
    breaks with the current "datatable" model to provide a more comprehensive view of the last visits
    on click it queries and displays the whois record of visitors ip 

I wrote this plugin to get a better overview of current visitors location, settings and so on. A comprehensive overview, which is not provided by the standard widgets - data was spread out in different widgets, but none showed a combined view of ip, settings, provider and referer per visit. The UI of the Live plugin was too confusing (at least for me).

Note, that it probably does not make much sense for high traffic sites.

