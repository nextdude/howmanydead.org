# Mission of HMD

The mission of HMD is to provide a social web service for tracking
estimates of the scope of political violence around the globe as
it happens in the hope of informing policy and public discourse.

How many people have been killed, maimed, orphaned, displaced or
otherwise grievously hurt by political violence in a given conflict,
over a given time period?  For instance, how many people were killed
near the Raba'a al Adiwiya mosque in Egypt on August 14, 2013? How
manu civilians were hurt in the violence following the 2009 Iranian
elections? These numbers are difficult to come by. The definition
of political violence is not widely agreed upon and there are no
definitive official sources of data to estimate its scope.

Governmental organizations are not good at tracking conflict data
because casualty estimates carry political costs that governments
are not willing to bear.  There are well known academic research
efforts, such as the Uppsala Conflict Data Program (UCDP) at Uppsala
University in Sweden, and the Center for International Development
and Conflict Management (CIDCM) at the University of Maryland in
the United States, that track casualty estimates, among other
variables, on an annual basis for conflicts that meet their definitions
of political violence.  The UCDP data is searchable and available
online for many conflicts through 2007 (as of the summer of 2010).
The CIDCM data is published in biennial reports, the most recent
of which (in 2010) can be purchased for $24.25 on Amazon and tracks
data through 2007.

In addition, there are well-known non-academic and non-governmental
organizations (NGOs), such as Amnesty International, that track
conflicts and their casualties and provide data via reports and
news stories.

All of this work, however, is contained in a diffuse body of web
sites, agency and academic reports that struggle to keep pace with
the evolution of political conflict across the globe.  HMD's goal
is to provide a way to organize data from these myriad sources, as
well as from social media, to track the scope of casualties from
political violence in as close to real-time as possible.

## What HMD Is Not

HMD is not a political blog. HMD does not advocate for any side in
a particular political conflict. HMD does not filter content on
this site with an editorial eye to slant coverage one way or the
other. While HMD was created to focus attention on highly complex
and controversial topics surrounding the casualty estimates that
comprise the site, it is important for HMD to remain impartial. HMD
is an information resource sustained by its users to help inform
its users.

By aggregating publicly available data sources with reports culled
through social network channels like Twitter and Facebook, HMD tries
to provide the best casualty estimates in as close to real time as
possible so our users can inform their personal opinions and public
policies on global political conflicts.

## Why HMD Is Useful

Consider the June 2009 Iranian election and the violence that
followed. There is scant information on casualty numbers, and the
estimates that exist are divergent. The Iranian government has
admitted to 36 deaths. CNN reported unofficial estimates of over
150 deaths in the protests on just one day (June 20th) of the
protests.

Use example of 2008 war in Gaza and Iranian political violence to
make the point that it's difficult to obtain estimates, but still
important to do so.  In the case of the Gaza War, the facts are
disputed. The UN has its numbers.  Various NGOs have their numbers.
The PA has its numbers. The IDF has its numbers. And there is
variation, even wide variation in these estimates. But all estimates
point to well over 1000 Palestinians killed in the 3 week war,
compared with 13 Israelis. That's a stark fact that should give
someone, somewhere food for thought, whether they be a policy maker
or a just someone talking to a friend over dinner.

Basic Model:

There's an OLTP model to capture reports of estimates. This model
captures as much metadata as possible both about the source of the
report as well as the casualty report itself. It captures these
reports from the following channels:

  web - a web form on hmd.org api - postings to a restful api email
  - an email to report@hmd.org twitter - tweets @hmd.org facebook
  - postings to hmd page and culled from member pages user feeds -
  rss feeds of users

On channels other than the web and api, we use microformats to embed
report data in the channel. So we parse the email or tweet or fb
posting or rss feed.  If the posting is from a recognized account,
we mark it as authenticated.  Otherwise, it is considered
unauthenticated. Unauthenticated reports can be viewed and summarized
just like authenticated ones. But b/c we track it, they can be
filtered. Reports that are initially unauthenticated can later be
authenticated by a user adding their channel to an authenticated
account.

Another kind of transaction we process online is voting for reports.
This allows users of the site to reveal the degree of their interest
in particular reports. It allows HMD to rank reports and show
trending issues.

Another kind of transaction we process online is commenting on
reports.  Comments are limited to 1024 characters, which is enough
for about 14 average english sentences, or 1-2 paragraphs. The
purpose of the limit is to prevent the site from becoming a wiki
about specific political conflicts and try to focus people on the
reports and the immediate issues surrounding them.*** WHAT DOES
THIS MEAN?

We also have OLAP model. This model is a basic star schema with the
main fact table being the casualty table. It has dimensions around
it involving metadata about both the casualty estimate itself (when
and where it occurred, what kind of casualty report (death, injury,
displacements, etc), what conflict it is associated with, which
side of the conflict the casualty is associated with, etc) as well
as the reporter of the estimate (channel, when/where/who report
came from, is it authenticated, number of votes, number of comments).

The OLAP table is what drives the output side of the web site.
People can define OLAP cubes that can be accessed by any Mondrian
front-end, like JPivot or OpenI. These cubes further, can be shared,
rated, commented on, etc. We provide several cubes ourselves that
are always available to people. Analysis from popular cubes are
shown prominently on the HMD site.


casualty table is main fact table dimensions include:
  source of report time of casualty location of casualty conflict


create table casualty (
  estimate int unsigned source (who reported it) time (when did it
  happen) location conflict_id

)

dimensions
  time location

