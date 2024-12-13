# Bachelor News Integration Danish site
Code from bachelor project in WordPress - Danish site

## Beskrivelse
Dette repository indeholder koden til hovedsitet. Dette site fungerer som kilde for nyheder og olietillæg, som andre sites henter via REST API.

## Projekt Struktur
- `/functions/api-integration.php`: Indeholder API integration og shortcodes
- `/css/style.css`: Stylesheet for nyhedsvisning og olietillæg

## Funktionalitet
- Henter danske nyheder fra hovedsitet (kategori 13)
- Henter og viser olietillæg (kategori 20)
- Viser nyheder med dansk datoformat
- Responsivt design med grid layout

## Shortcodes
Koden implementerer to shortcodes:
- [vis_nyheder] - Viser danske nyheder
- [vis_olietillaeg] - Viser olietillæg

## Installation
- Kopier kodefilerne til dit WordPress-tema
- Opdater API-credentials i hent_data_fra_api() funktionen
- Inkluder stylesheet i dit tema
- Brug shortcodes på de ønskede sider

## Teknologier
- WordPress
- PHP
- REST API
- WordPress Shortcodes

## Udvikler
Søs Wind
