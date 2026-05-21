# Turn Off AI Features

Adds an option to the General Settings page to turn off AI features in WordPress.

---

## Demo

[![Demo Video](https://img.youtube.com/vi/ZKoHZ3IbMnU/0.jpg)](https://youtu.be/ZKoHZ3IbMnU)

## Description

Turn Off AI Features lets you control AI functionality in WordPress without touching code. It hooks into the `wp_supports_ai` filter at priority 1000 and returns `false` when the option is enabled.

**Features:**

- Toggle AI on or off from **Settings > General** — no deactivation needed.
- WP-CLI support: `wp toaif disable` / `wp toaif enable` / `wp toaif status`.
- Settings link on the Plugins page for quick access.
- Runs at priority 1000, overriding other plugins that may enable AI.

---

## Installation

1. Upload the `turn-off-ai-features` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings > General** and check "Turn off AI features on this site".

---

## Usage

### Admin Dashboard

1. Go to **Settings > General**
2. Check the option: "Turn off AI features on this site"
3. Click Save Changes

### WP-CLI

```bash
# Turn off AI features
wp toaif disable

# Turn on AI features
wp toaif enable

# Check status
wp toaif status
```
