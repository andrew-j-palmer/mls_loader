# mls_loader

MLS Loader is a utlity that interfaces with virtually any MLS (Multiple Listing Service, a staple of the Real Estate Industry)
server and conforms the resultant data to a common database. With this, Real Estate Marketing firms can procedurally create
products with the confidence that data from Connecticut will generate the same products as data from California. The repo
includes a CLI to generate code for a new server connection, and once the generated files are configured, the utility
downloads pertinent listing data and stores it in a MySQL database.

This version of MLS Loader is probably 80% complete as I am not comfortable sharing the full proprietary version.

MLS Server interface is made possible via the PHRETS library by Troy Davisson. https://github.com/troydavisson/PHRETS

The CLI is made practical via Commando by Nate Good. https://github.com/nategood/commando

(both libraries added with Composer)
