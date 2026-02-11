composer create-project laravel/laravel souq-iran
cd souq-iran
composer require laravel/sanctum laravel/reverb pusher/pusher-php-server filament/filament "livewire/livewire:^3.0" guzzlehttp/guzzle
php artisan sanctum:install
php artisan filament:install --panels
npm install && npm run build