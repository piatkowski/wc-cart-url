# Installation

1. Download repository as ZIP file
2. In Wordpress, go to Plugins > Add New
3. Click on "Upload Plugin" and select ZIP file

# Usage

1. This plugin creates button "Share this cart" on the Cart page. The button is showed only for Store Manager, Admin and any user which has capability to "manage_woocommerce"
2. The button saves current cart session to file in temp directory (see: get_temp_dir()) and creates link to share the cart https://mystore.pl/cart/?share=hash
3. After opening the link, the current cart session is replaced with the data previously saved to the temporary file.
