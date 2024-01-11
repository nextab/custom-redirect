# custom-redirect
Extends WordPress functionality and allows you to define redirect with a number of meta fields on a per page / post basis.

# Installation:
- upload in plugins folder
- define meta fields (either use the skin in the folder /skins to import them for JetEngine or create your own in Advanced Custom Fields or just use generic custom fields in WordPress)
- go to a page and set the parameters

# Explanations of the custom fields:
### nxt-page-visibility
boolean - true / false -> whether or not to apply redirect logic

### nxt-pv-logged-in
boolean - true / false -> redirect non-logged in users (useful for "manage my account" pages)

### nxt-pv-logged-out
boolean - true / false -> redirect logged in users (useful for "register now / login" pages)

### nxt-pv-redirect-login
string -> URL of the page where you want to redirect users that meet the above criteria

### nxt-pv-user-roles
string -> define the capabilities that a user needs to meet if you want them to NOT get redirected
Examples: 
- "manage_options" -> all users below admin will get redirected
- "edit_posts" -> all users below author (= subscribers) will get redirected

See https://wordpress.org/documentation/article/roles-and-capabilities/ for reference

### nxt-pv-subs-only
boolean - true / false -> redirect if the user is not a "confirmed user" (= has custom capability "can_signup_for_events")
You can hack this functionality if you have custom roles / permissions in your WordPress environment and you want to redirect those users that don't meet the criteria.

### nxt-pv-redirect-permission
string -> URL of the page where you want to redirect users that meet the above criteria

# Important notes:
The plugin FIRST applies the logic for "redirect logged in users" / "redirect NON logged in users" and only if it doesn't redirect, it starts applying the logic for certain user roles.
