Obsydian-Falcon

# Plane Spotting #
My friend swears he saw a Russian plane in the US this June, but I don't believe him. He also says he saw it was parked next to a plane owned by an infamous former president! Can you find the squawk number of this Russian plane, the airport code of where the plane was finally parked at, as well as the registration number of the plane owned by the celebrity? Oh, also find the city the Russian plane was closest to at 21:07:40 Z during its flights in the US! Flag format: csawctf{SquawkNumber_AirportCode_RegistrationNumber_CityName}

## Infamous Former President ##
As far as I'm aware, there are three presidents who have truly reached some level of "infamy". Firstly, there's Nixon with the Watergate scandal. Secondly, there's Clinton, who swears "I did not have sexual relations with that woman!". Third, there's Donald Trump, who's infamous for a slew of scandals and being charged with 34 counts of felony. It seems doubtful that Russian planes would be on American soil during Nixon's time, and Clinton might be a pick, but for now, let's stick with Trump.

Hitting Google with: "Trump Russian Jet" as the query nets us the following news story:
* https://www.msn.com/en-us/news/politics/nothing-to-see-here-internet-explodes-after-trump-jet-parks-next-to-russian-aircraft/ar-BB1p5KVj
Ok, well that was easy. Now we just need to see if there's a link to any pictures of the Jets together.
    * Yeeup, there's a photo posted on twitter by @PenguinSix with the Jets next to each other: https://x.com/PenguinSix/status/1806740535685664847
    * Looking closely, we see that the Russian Jet has the Sqawk Number of: **RA-96018**
    * This article also tells us that the planes were spotted at Dulles International Airport, which google tells us has an Airport code of: **IDA**
Alright, that's the Russian Jet found and we also need the RN of Trump's jet, a quick google of "Trump Force One Registration Number" yields the following:
* **N757AF**
Our flag so far: csawctf{RA96018_IDA_N757AF_CityName}

## The Hunt for Red October (plane edition) ##
Now we've gotta find where another Russian plane that was in American airspace only a couple days before the photo was snapped. How do we do this? Well, turns out, tracking jets is a favorite hobby of Aviation enthusiasts (you might of hear of "Elon Jet Tracker" or Taylor Swift paying to obfuscate her movements). So, let's check out some flight tracking websites shall we?
* It looks like this site will let us track some planes without having to pay!: https://globe.adsbexchange.com/
First though, we need the registration number of the second plane. The second plane can be found by googling: "russian planes traveling to US june 2024" That gets us the registration number **RA-96019**.
    * Putting in the Russian Registration Number, we can now filter by date and see the plane's progress.
    * Now we can click on the line showing the plane's flight path and see where it was at 21:07:40 Z, which would be Trenton.
Our flag so far: csawctf{RA96018_IDA_N757AF_Trenton}

## That's It!!! ##
We now have all the information to submit the flag: csawctf{RA96018_IDA_N757AF_Trenton}


