<?php

namespace App\Support;

use Illuminate\Support\Str;

class MarketplaceCategoryCatalog
{
    public static function tree(): array
    {
        return [
            self::category('Top Up Game', 'top-up-game', 'app/public/ikon-kategori/topup-game.svg', [
                self::child('top-up-game', 'Mobile Legends', 'app/public/ikon-subkategori/1. Top Up Game/mobile-legends.webp'),
                self::child('top-up-game', 'Zepeto', 'app/public/ikon-subkategori/1. Top Up Game/zepeto.webp'),
                self::child('top-up-game', 'Blood Strike', 'app/public/ikon-subkategori/1. Top Up Game/blood-strike.webp'),
                self::child('top-up-game', 'Valorant', 'app/public/ikon-subkategori/1. Top Up Game/valorant.webp'),
                self::child('top-up-game', 'Garena Free Fire', 'app/public/ikon-subkategori/1. Top Up Game/garena-free-fire.webp'),
                self::child('top-up-game', 'Honkai: Star Rail', 'app/public/ikon-subkategori/1. Top Up Game/honkai-star-rail.webp'),
                self::child('top-up-game', 'Magic Chess: Go Go', 'app/public/ikon-subkategori/1. Top Up Game/magic-chess-go-go.webp'),
                self::child('top-up-game', 'LivU', 'app/public/ikon-subkategori/1. Top Up Game/livu.webp'),
                self::child('top-up-game', 'Genshin Impact', 'app/public/ikon-subkategori/1. Top Up Game/genshin-impact.webp'),
                self::child('top-up-game', 'PUBG Mobile', 'app/public/ikon-subkategori/1. Top Up Game/pubg-mobile.webp'),
                self::child('top-up-game', 'Garena Free Fire MAX', 'app/public/ikon-subkategori/1. Top Up Game/garena-free-fire-max.webp'),
                self::child('top-up-game', 'Honor of Kings', 'app/public/ikon-subkategori/1. Top Up Game/honor-of-kings.webp'),
            ]),
            self::category('Game Key', 'game-key', 'app/public/ikon-kategori/game-key.svg', [
                self::child('game-key', 'Brawlhalla', 'app/public/ikon-subkategori/2. Game Key/brawlhalla.webp'),
                self::child('game-key', 'Palworld', 'app/public/ikon-subkategori/2. Game Key/palworld.webp'),
                self::child('game-key', '.hack//G.U. Last Recode', 'app/public/ikon-subkategori/2. Game Key/hack-gu-last-recode.webp'),
                self::child('game-key', '911 Operator', 'app/public/ikon-subkategori/2. Game Key/911-operator.webp'),
                self::child('game-key', 'Age of Empires II: Definitive Edition', 'app/public/ikon-subkategori/2. Game Key/age-of-emipres-ii-definitive-edition.webp'),
                self::child('game-key', 'Age of Empires III: Definitive Edition', 'app/public/ikon-subkategori/2. Game Key/age-of-empires-iii-definitive-edition.webp'),
                self::child('game-key', 'ARK: Survival Ascended', 'app/public/ikon-subkategori/2. Game Key/ark-survival-ascended.webp'),
                self::child('game-key', 'Arma 3', 'app/public/ikon-subkategori/2. Game Key/arma-3.webp'),
                self::child('game-key', "Assassin's Creed Mirage", 'app/public/ikon-subkategori/2. Game Key/assassins-creed-mirage.webp'),
                self::child('game-key', "Assassin's Creed® Odyssey", 'app/public/ikon-subkategori/2. Game Key/assasins-creed-odyssey.webp'),
                self::child('game-key', 'Assassin’s Creed Shadows', 'app/public/ikon-subkategori/2. Game Key/assassins-creed-shadows.webp'),
                self::child('game-key', 'Assetto Corsa', 'app/public/ikon-subkategori/2. Game Key/assetto-corsa.webp'),
                self::child('game-key', 'Atlas Fallen: Reign Of Sand', 'app/public/ikon-subkategori/2. Game Key/atlas-fallen-reign-of-sand.webp'),
                self::child('game-key', 'Atomic Heart', 'app/public/ikon-subkategori/2. Game Key/atomic-heart.webp'),
                self::child('game-key', 'Back 4 Blood', 'app/public/ikon-subkategori/2. Game Key/back-4-blood.webp'),
                self::child('game-key', 'Banishers: Ghosts of New Eden', 'app/public/ikon-subkategori/2. Game Key/banishers-ghosts-of-new-eden.webp'),
                self::child('game-key', 'Battlefield™ V', 'app/public/ikon-subkategori/2. Game Key/battlefield-v.webp'),
                self::child('game-key', 'Batman: Arkham Asylum Game of the Year Edition', 'app/public/ikon-subkategori/2. Game Key/batman-arkham-asylum-game-of-the.webp'),
                self::child('game-key', 'Batman™: Arkham Origins', 'app/public/ikon-subkategori/2. Game Key/batman-arkham-origins.webp'),
                self::child('game-key', 'Batman: Arkham City - Game of the Year Edition', 'app/public/ikon-subkategori/2. Game Key/batman-arkham-city-game-of-the.webp'),
            ]),
            self::category('Roblox Games', 'roblox-games', 'app/public/ikon-kategori/roblox-games.svg', [
                self::child('roblox-games', 'Roblox', 'app/public/ikon-subkategori/3. Roblox Games/roblox.svg'),
                self::child('roblox-games', 'Fish It!', 'app/public/ikon-subkategori/3. Roblox Games/fish-it.webp'),
                self::child('roblox-games', 'Blox Fruits', 'app/public/ikon-subkategori/3. Roblox Games/blox-fruits.webp'),
                self::child('roblox-games', 'Fisch', 'app/public/ikon-subkategori/3. Roblox Games/fisch.webp'),
                self::child('roblox-games', 'Steal A Brainrot', 'app/public/ikon-subkategori/3. Roblox Games/steal-a-brainrot.webp'),
                self::child('roblox-games', 'Escape Tsunami For Brainrots', 'app/public/ikon-subkategori/3. Roblox Games/escape-tsunami-for-brainrots.webp'),
                self::child('roblox-games', 'Adopt Me Trading Hub', 'app/public/ikon-subkategori/3. Roblox Games/adopt-me-trading-hub.webp'),
                self::child('roblox-games', 'Blade Ball', 'app/public/ikon-subkategori/3. Roblox Games/blade-ball.webp'),
                self::child('roblox-games', 'Grow A Garden', 'app/public/ikon-subkategori/3. Roblox Games/grow-a-garden.webp'),
                self::child('roblox-games', 'Rivals', 'app/public/ikon-subkategori/3. Roblox Games/rivals.webp'),
                self::child('roblox-games', 'Bee Swarm Simulator', 'app/public/ikon-subkategori/3. Roblox Games/bee-swarm-simulator.webp'),
                self::child('roblox-games', 'Anime Vanguards', 'app/public/ikon-subkategori/3. Roblox Games/anime-vanguards.webp'),
            ]),
            self::category('Akun', 'akun', 'app/public/ikon-kategori/akun.svg', [
                self::child('akun', 'Clash of Clans', 'app/public/ikon-subkategori/4. Akun/clash-of-clans.webp'),
                self::child('akun', 'Steal A Brainrot', 'app/public/ikon-subkategori/4. Akun/steal-a-brainrot.webp'),
                self::child('akun', 'Mobile Legends', 'app/public/ikon-subkategori/4. Akun/mobile-legends.webp'),
                self::child('akun', 'Roblox', 'app/public/ikon-subkategori/4. Akun/roblox.svg'),
                self::child('akun', 'Garena Free Fire', 'app/public/ikon-subkategori/4. Akun/garena-free-fire.webp'),
                self::child('akun', 'Genshin Impact', 'app/public/ikon-subkategori/4. Akun/genshin-impact.webp'),
                self::child('akun', 'Blox Fruits', 'app/public/ikon-subkategori/4. Akun/blox-fruits.webp'),
                self::child('akun', 'Honkai: Star Rail', 'app/public/ikon-subkategori/4. Akun/honkai-star-rail.webp'),
                self::child('akun', 'One Piece Bounty Rush', 'app/public/ikon-subkategori/4. Akun/one-piece-bounty-rush.webp'),
                self::child('akun', 'Dead Rails', 'app/public/ikon-subkategori/4. Akun/dead-rails.webp'),
            ]),
            self::category('Voucher', 'voucher', 'app/public/ikon-kategori/voucher.svg', [
                self::child('voucher', 'Steam', 'app/public/ikon-subkategori/5. Voucher/steam.webp'),
                self::child('voucher', 'Roblox', 'app/public/ikon-subkategori/5. Voucher/roblox.svg'),
                self::child('voucher', 'Google Play Gift Card', 'app/public/ikon-subkategori/5. Voucher/google-play-gift-card.webp'),
                self::child('voucher', 'ExitLag', 'app/public/ikon-subkategori/5. Voucher/exitlag.webp'),
                self::child('voucher', 'Playstation Network Card', 'app/public/ikon-subkategori/5. Voucher/playstation-network-card.webp'),
                self::child('voucher', 'Cherry Credits', 'app/public/ikon-subkategori/5. Voucher/cherry-credits.webp'),
                self::child('voucher', 'Nintendo', 'app/public/ikon-subkategori/5. Voucher/nintendo.webp'),
                self::child('voucher', 'Razer Gold', 'app/public/ikon-subkategori/5. Voucher/razer-gold.webp'),
                self::child('voucher', 'Valorant', 'app/public/ikon-subkategori/5. Voucher/valorant.webp'),
                self::child('voucher', 'Redfinger', 'app/public/ikon-subkategori/5. Voucher/redfinger.webp'),
                self::child('voucher', 'VSPhone', 'app/public/ikon-subkategori/5. Voucher/vsphone.webp'),
                self::child('voucher', 'Point Blank Beyond Limits', 'app/public/ikon-subkategori/5. Voucher/point-blank-beyond-limits.webp'),
            ]),
            self::category('Koin Game', 'koin-game', 'app/public/ikon-kategori/koin-game.svg', [
                self::child('koin-game', 'Growtopia', 'app/public/ikon-subkategori/6. Koin Game/growtopia.webp'),
                self::child('koin-game', 'Seal Online Blades of Destiny', 'app/public/ikon-subkategori/6. Koin Game/seal-online-blades-of-destiny.webp'),
                self::child('koin-game', 'Toram Online', 'app/public/ikon-subkategori/6. Koin Game/toram-online.webp'),
                self::child('koin-game', 'Roblox', 'app/public/ikon-subkategori/6. Koin Game/roblox.svg'),
                self::child('koin-game', 'Albion Online', 'app/public/ikon-subkategori/6. Koin Game/albion-online.webp'),
                self::child('koin-game', 'Pet Simulator 99!', 'app/public/ikon-subkategori/6. Koin Game/pet-simulator-99.webp'),
                self::child('koin-game', 'Grow A Garden', 'app/public/ikon-subkategori/6. Koin Game/grow-a-garden.webp'),
                self::child('koin-game', 'Blade Ball', 'app/public/ikon-subkategori/6. Koin Game/blade-ball.webp'),
                self::child('koin-game', 'Dragon Nest Classic Sea', 'app/public/ikon-subkategori/6. Koin Game/dragon-nest-classic-sea.webp'),
                self::child('koin-game', 'Fisch', 'app/public/ikon-subkategori/6. Koin Game/fisch.webp'),
            ]),
            self::category('Item', 'item', 'app/public/ikon-kategori/item.svg', [
                self::child('item', 'Dota 2', 'app/public/ikon-subkategori/7. Item/dota-2.webp'),
                self::child('item', 'Roblox', 'app/public/ikon-subkategori/7. Item/roblox.svg'),
                self::child('item', 'Blox Fruits', 'app/public/ikon-subkategori/7. Item/blox-fruits.webp'),
                self::child('item', 'Grow A Garden', 'app/public/ikon-subkategori/7. Item/grow-a-garden.webp'),
                self::child('item', 'Bubble Gum Simulator Infinity', 'app/public/ikon-subkategori/7. Item/bubble-gum-simulator-infinity.webp'),
                self::child('item', 'Survive The Killer', 'app/public/ikon-subkategori/7. Item/survive-the-killer.webp'),
                self::child('item', 'Murder Mystery 2', 'app/public/ikon-subkategori/7. Item/murder-mystery-2.webp'),
                self::child('item', 'Adopt Me Trading Hub', 'app/public/ikon-subkategori/7. Item/adopt-me-trading-hub.webp'),
                self::child('item', 'Fisch', 'app/public/ikon-subkategori/7. Item/fisch.webp'),
                self::child('item', 'Blue Lock Rivals', 'app/public/ikon-subkategori/7. Item/blue-lock-rivals.webp'),
            ]),
            self::category('Joki', 'joki', 'app/public/ikon-kategori/joki.svg', [
                self::child('joki', 'Mobile Legends', 'app/public/ikon-subkategori/8. Joki/mobile-legends.webp'),
                self::child('joki', 'Genshin Impact', 'app/public/ikon-subkategori/8. Joki/genshin-impact.webp'),
                self::child('joki', 'Blox Fruits', 'app/public/ikon-subkategori/8. Joki/blox-fruits.webp'),
                self::child('joki', 'Honkai: Star Rail', 'app/public/ikon-subkategori/8. Joki/honkai-star-rail.webp'),
                self::child('joki', 'Anime Adventures', 'app/public/ikon-subkategori/8. Joki/anime-adventures.webp'),
                self::child('joki', 'Anime Last Stand', 'app/public/ikon-subkategori/8. Joki/anime-last-stand.webp'),
                self::child('joki', "Sol's RNG", 'app/public/ikon-subkategori/8. Joki/sols-rng.webp'),
                self::child('joki', 'Fisch', 'app/public/ikon-subkategori/8. Joki/fisch.webp'),
                self::child('joki', 'Anime Reborn', 'app/public/ikon-subkategori/8. Joki/anime-reborn.webp'),
                self::child('joki', 'Jujutsu Infinite', 'app/public/ikon-subkategori/8. Joki/jujutsu-infinite.webp'),
            ]),
            self::category('Top Up Login', 'top-up-login', 'app/public/ikon-kategori/topup-login.webp', [
                self::child('top-up-login', 'Roblox', 'app/public/ikon-subkategori/9. Top Up Login/roblox.svg'),
                self::child('top-up-login', 'Genshin Impact', 'app/public/ikon-subkategori/9. Top Up Login/genshin-impact.webp'),
                self::child('top-up-login', 'eFootball Mobile', 'app/public/ikon-subkategori/9. Top Up Login/efootball-mobile.webp'),
                self::child('top-up-login', 'Wuthering Waves', 'app/public/ikon-subkategori/9. Top Up Login/wuthering-waves.webp'),
                self::child('top-up-login', 'Pokemon GO', 'app/public/ikon-subkategori/9. Top Up Login/pokemon-go.webp'),
                self::child('top-up-login', 'Honkai: Star Rail', 'app/public/ikon-subkategori/9. Top Up Login/honkai-star-rail.webp'),
                self::child('top-up-login', 'Clash Royale', 'app/public/ikon-subkategori/9. Top Up Login/clash-royale.webp'),
                self::child('top-up-login', 'Hay Day', 'app/public/ikon-subkategori/9. Top Up Login/hay-day.webp'),
                self::child('top-up-login', 'Tree of Savior: Neverland', 'app/public/ikon-subkategori/9. Top Up Login/tree-of-savior-neverland.webp'),
                self::child('top-up-login', 'Zepeto', 'app/public/ikon-subkategori/9. Top Up Login/zepeto.webp'),
            ]),
            self::category('Streaming', 'streaming', 'app/public/ikon-kategori/streaming.svg', [
                self::child('streaming', 'Spotify', 'app/public/ikon-subkategori/10. Streaming/spotify.webp'),
                self::child('streaming', 'Viu', 'app/public/ikon-subkategori/10. Streaming/viu.webp'),
                self::child('streaming', 'YouTube Premium', 'app/public/ikon-subkategori/10. Streaming/youtube-premium.webp'),
                self::child('streaming', 'Apple Music', 'app/public/ikon-subkategori/10. Streaming/apple-music.webp'),
                self::child('streaming', 'Disney+ Hotstar', 'app/public/ikon-subkategori/10. Streaming/disney-hotstar.webp'),
                self::child('streaming', 'iQIYI', 'app/public/ikon-subkategori/10. Streaming/iqiyi.webp'),
                self::child('streaming', 'WeTV', 'app/public/ikon-subkategori/10. Streaming/wetv.webp'),
                self::child('streaming', 'Bilibili.tv', 'app/public/ikon-subkategori/10. Streaming/bilibilitv.webp'),
                self::child('streaming', 'Netflix', 'app/public/ikon-subkategori/10. Streaming/netflix.webp'),
                self::child('streaming', 'Loklok', 'app/public/ikon-subkategori/10. Streaming/loklok.webp'),
            ]),
            self::category('Live Show', 'live-show', 'app/public/ikon-kategori/live-show.svg', [
                self::child('live-show', 'Poppo Live', 'app/public/ikon-subkategori/11. Live Show/poppo-live.webp'),
                self::child('live-show', 'BIGO Live', 'app/public/ikon-subkategori/11. Live Show/bigo-live.webp'),
                self::child('live-show', 'Papaya Live', 'app/public/ikon-subkategori/11. Live Show/papaya-live.webp'),
                self::child('live-show', 'Lemo', 'app/public/ikon-subkategori/11. Live Show/lemo.webp'),
                self::child('live-show', 'WeSing', 'app/public/ikon-subkategori/11. Live Show/wesing.webp'),
                self::child('live-show', 'MixU', 'app/public/ikon-subkategori/11. Live Show/mixu.webp'),
                self::child('live-show', 'MLiveU', 'app/public/ikon-subkategori/11. Live Show/mliveu.webp'),
                self::child('live-show', 'StarMaker', 'app/public/ikon-subkategori/11. Live Show/starmaker.webp'),
                self::child('live-show', 'Tango', 'app/public/ikon-subkategori/11. Live Show/tango.webp'),
                self::child('live-show', 'Bermuda', 'app/public/ikon-subkategori/11. Live Show/bermuda.webp'),
            ]),
            self::category('Pulsa & Utilitas', 'pulsa-utilitas', 'app/public/ikon-kategori/pulsa-utilitas.svg', [
                self::child('pulsa-utilitas', 'Axis', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/axis.webp'),
                self::child('pulsa-utilitas', 'Token PLN', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/token-pln.webp'),
                self::child('pulsa-utilitas', 'Telkomsel', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/telkomsel.webp'),
                self::child('pulsa-utilitas', 'Tri', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/tri.webp'),
                self::child('pulsa-utilitas', 'Indosat Ooredoo', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/indosat-ooredoo.webp'),
                self::child('pulsa-utilitas', 'by.U', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/byu.webp'),
                self::child('pulsa-utilitas', 'Alfamart', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/alfamart.webp'),
                self::child('pulsa-utilitas', 'Smartfren', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/smartfren.webp'),
                self::child('pulsa-utilitas', 'Wifi.id', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/wifiid.webp'),
                self::child('pulsa-utilitas', 'XL', 'app/public/ikon-subkategori/12. Pulsa & Utilitas/xl.webp'),
            ]),
            self::category('Aplikasi & Software', 'aplikasi-software', 'app/public/ikon-kategori/aplikasi-software.svg', [
                self::child('aplikasi-software', 'Redfinger', 'app/public/ikon-subkategori/13. Aplikasi & Software/redfinger.webp'),
                self::child('aplikasi-software', 'Canva', 'app/public/ikon-subkategori/13. Aplikasi & Software/canva.webp'),
                self::child('aplikasi-software', 'ChatGPT', 'app/public/ikon-subkategori/13. Aplikasi & Software/chatgpt.webp'),
                self::child('aplikasi-software', 'VSPhone', 'app/public/ikon-subkategori/13. Aplikasi & Software/vsphone.webp'),
                self::child('aplikasi-software', 'Express VPN', 'app/public/ikon-subkategori/13. Aplikasi & Software/express-vpn.webp'),
                self::child('aplikasi-software', 'Alight Motion', 'app/public/ikon-subkategori/13. Aplikasi & Software/alight-motion.webp'),
                self::child('aplikasi-software', 'HideMyAss VPN', 'app/public/ikon-subkategori/13. Aplikasi & Software/hidemyass-vpn.webp'),
                self::child('aplikasi-software', 'Discord', 'app/public/ikon-subkategori/13. Aplikasi & Software/discord.webp'),
                self::child('aplikasi-software', 'ExitLag', 'app/public/ikon-subkategori/13. Aplikasi & Software/exit-lag.webp'),
                self::child('aplikasi-software', 'Zoom Cloud Meetings', 'app/public/ikon-subkategori/13. Aplikasi & Software/zoom-cloud-meetings.webp'),
            ]),
        ];
    }

    public static function parentOptions(): array
    {
        return array_map(static fn (array $category): array => [
            'name' => $category['name'],
            'slug' => $category['slug'],
            'image' => $category['image'],
            'children' => $category['children'],
        ], self::tree());
    }

    public static function typeLabelMap(): array
    {
        $map = [];

        foreach (self::tree() as $category) {
            foreach ($category['children'] as $child) {
                $map[$child['slug']] = $child['name'];
            }
        }

        $map += [
            'topup' => 'Top Up Game',
            'item' => 'Item',
            'akun' => 'Akun',
            'voucher' => 'Voucher',
            'gamekey' => 'Game Key',
        ];

        return $map;
    }

    public static function leafSlugs(): array
    {
        return array_keys(self::typeLabelMap());
    }

    public static function labelForType(?string $type): string
    {
        $type = (string) $type;

        return self::typeLabelMap()[$type] ?? ($type !== '' ? Str::headline(str_replace(['-', '_'], ' ', $type)) : 'Item');
    }

    public static function category(string $name, string $slug, string $image, array $children = []): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'image' => $image,
            'children' => $children,
        ];
    }

    public static function child(string $parentSlug, string $name, string $image): array
    {
        return [
            'name' => $name,
            'slug' => $parentSlug . '-' . Str::slug($name),
            'image' => $image,
        ];
    }
}