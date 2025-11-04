# V1t Duck CTF: The Forgotten Inventory

Author: Walker Dauphin II (obsydian_falcon)

## Description

* Category: OSINT

> In the summer of 2007, a massive archive quietly surfaced — a meticulous ledger of items that once rolled across desert sands under foreign command. The file was structured, line after line, page after page, each entry tied to a unit whose banners flew far from home.
> Someone wasn’t happy about its release. A message was sent, demanding its silence — a digital plea from a uniformed gatekeeper. The request was denied.
> Your task is to uncover the sender of that plea.
> Clues hide in old public archives — look for a tabular trail documenting what was once called Operation Freedom, catalogued in a comma-separated vault of** 1,996 pages** worth of equipment.
> Some say the sands between two nations hold the real context — where east met west, and war turned into a spreadsheet.
> Find the** email address** of the official who tried to bury the list.
> Format: v1t{hello.hi@ehatever.com}

> [HINT](https://www.youtube.com/watch?v=dQw4w9WgXcQ)

Files: N/A

## Tools used

* google
* wikipedia

## Initial Analysis

First, I googled "Operation Freedom" and found a wikipedia article as the first hit, "Operation Enduring Freedom". Within that wikipedia article, I found a link to the military version of the events. Both links are below.

* [wikipedia](https://en.wikipedia.org/wiki/Operation_Enduring_Freedom)
* [navy.mil](ihttps://www.history.navy.mil/browse-by-topic/wars-conflicts-and-operations/middle-east/operation-enduring-freedom.html)

## Solution

After going through the links, I figured that if somebody had "leaked" information, that information would probably be on the internet's hub of poorly secured confidential data, Wikileaks. So I went to the wikileaks site and entered the advanced search. The challenge description mentions that the article surfaced in 2007, so I used Wikileaks' advanced search to see what I could find.
* Filtered by "release" from Jan 2006 to Jan 2009 (just to be sure).
    * [wikileaks](https://search.wikileaks.org/advanced?any_of=&page=2&exact_phrase=&query=Operation+Freedom&released_date_end=2008-01-01&document_date_start=&order_by=most_relevant&released_date_start=2006-01-01&new_search=True&exclude_words=&document_date_end=)
* After filtering, found this document that seemed interesting: [US Military Equipment in Iraq (2007)](https://wikileaks.org/wiki/US_Military_Equipment_in_Iraq_(2007))
* This document was an overview of the following 2000-page US military leak consisting of names, group structure, and theatre equipent registerd to all units in Iraq with the US Army.

After finding the document mentioned above on Wikileaks, I followed the links to the relevant `.xls` and `.csv` files. Upon following the [link](https://wikileaks.org/wiki/Iraq_OIF_Property_List.csv) I arrived at a page that contained the `.csv` file and a notice from Wikileaks admins. This message was essentially a notification that the information had been requested to be taken down by military personnel, but Wikileaks refused and put the content of the email up as proof of the document's legitimacy.

This email contains the information we need.

#### The Email in question

```
From: "Hoskins, David J CW2 MIL USA FORSCOM" <david.j.hoskins@us.army.mil>                                      
To: <wikileaks@sunshinepress.org>
Cc: "Rowe, Kirsten A 1LT MIL USA FORSCOM" <kirsten.rowe@us.army.mil>
Privacy: yes                                                
Privacy: yes                                               
Date: Thu, 26 Jun 2008 18:49:10 +0100 (BST)                                                                                                                  
Classification:  UNCLASSIFIED
Caveats: NONE                                                                                                                                                

All,                                                                                                                                                         
I have discovered a web page on your site that needs to be shut down
immediately due to confidentiality concerns.                                                                                                                 

http://wikileaks.org/wiki/B_BTRY_2-32_FA_REG_(WA1LB0)                                                                                                        

This is considered sensitive information and need to be removed.  If
there is a contact number that I can speak to someone with immediately,
please respond to this email.  I need to be informed on how this      
information was posted and who posted it.                                                                                                                    
Thank you                                                                                                                                                    

Hoskins, David
CW2, SC      
4/1 IBCT Signal Systems Technician
785-239-9270
Classification:  UNCLASSIFIED                                                                                
Caveats: NONE
```

The flag is the email of the officer requesting the data removal: `v1t{david.j.hoskins@us.army.mil}`
