Obsydian-Falcon

# Authentic Chinese Food #
I visited this gourmet restaurant a while back, but I'm worried about the health and safety rating of it. Could you check it for me? Oh, and before I forget, could you tell me when the building was built, as well as the name of the LLC that owns it? Flag Format: csawctf{HealthGrade_YearBuilt_LLCName} (Name does not include LLC, replace all spaces with _)
![image](panda.jpg)

## Let's Do some Pixel Peeking First ##
Looking at the image provided, there's quite a lot to go on. The restuarant is Panda Express, it appears to be part of a whole building rather than standing alone, and there's a phone number in the upper-right that we can take a look at. 
 * Looking at the phone number, the **718** Part stands out quite a bit. Let's google that and see which area code it belongs to.
 * According to Wikipedia, that area code belongs to New York City, specifically the boroughs of "The Bronx", "Brooklyn", "Queens", and "Statan Island"

We now know the rough area in which the building is located, now we just need to find this specific restaurant. To do that, we can use google maps and go through each borough, finding the Panda Express locations in each one and using the "streetview" tool to see if the restaurants we find match the one in the photo.
 * Alright, it looks like the one we're looking for is in Brooklyn NY, at **423 Fulton St, Brooklyn, NY 11201**

## Time to Find Some Grades ##
The first part of the flag is the grade given to this particular restaurant. So we can again use Google to see what grade was most recently awarded to this location.
 * This site seems promising: https://www.nyc.gov/site/doh/services/restaurant-grades.page
 * Turns out they have a neat search feature that allows us to search restaurants by name, food type, and address.
 * According to this government site, the health-grade given to this particular Panda Express was that of **PENDING**.
    * 1) 	Cold TCS food item held above 41 °F; smoked or processed fish held above 38 °F; intact raw eggs held above 45 °F; or reduced oxygen packaged (ROP) TCS foods held above required temperatures except during active necessary preparation.
    * 2) 	Non-food contact surface or equipment made of unacceptable material, not kept clean, or not properly sealed, raised, spaced or movable to allow accessibility for cleaning on all sides, above and underneath the unit.
    * Yikes.
Our flag so far: csawctf{PENDING_YearBuilt_LLCName}

## Who Built this City? ##
Now we need information on who/when the building housing this Panda Express was built. For that, Google is yet again our friend (at least in incognito mode).
 * Searching: "423 Fulton St, Brooklyn, NY 11201 history" Gives us an immediate hit on this page: https://www.brownstoner.com/architecture/building-of-the-day-423-fulton-street/
 * Looking at the breakdown of information, the building was designed by George and Edward Blum back in the earlier 20th century. Apparently it was finally completed in **1931**.
 History and Architecture, a powerful combination for any nerd to have.
Our flag so far: csawctf{PENDING_1931_LLCName}

## Who owns this historic piece of real estate? ##
After changing our search Query to include "owner" in lieu of "history", a couple links down we find some sites, but the most helpful is the NYC department of finace: "https://a836-pts-access.nyc.gov/care/Datalets/Datalet.aspx?sIndex=2&idx=1" 
* This site lists "BNN FULTON FLUSHING OWNER LLC" as the owner of the property
Our flag so far: csawctf{PENDING_1931_BNN_FULTON_FLUSHING_OWNER}

## We got it! ##
csawctf{PENDING_1931_BNN_FULTON_FLUSHING_OWNER} is the flag, and we've completed the challenge!

