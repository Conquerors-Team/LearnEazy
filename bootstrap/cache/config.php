<?php return array (
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://phpstack-775163-2636574.cloudwaysapps.com',
    'timezone' => 'Asia/Calcutta',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'base64:ffcbykUfwnqhgqd4iOw5AdKwv5QxNmqbYSupImcVuUg=',
    'cipher' => 'AES-256-CBC',
    'log' => 'single',
    'log_level' => 'debug',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Milon\\Barcode\\BarcodeServiceProvider',
      23 => 'SimpleSoftwareIO\\SMS\\SMSServiceProvider',
      24 => 'Anhskohbo\\NoCaptcha\\NoCaptchaServiceProvider',
      25 => 'Shipu\\Themevel\\Providers\\ThemevelServiceProvider',
      26 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
      27 => 'Laravel\\Tinker\\TinkerServiceProvider',
      28 => 'Laravel\\Cashier\\CashierServiceProvider',
      29 => 'Cmgmyr\\Messenger\\MessengerServiceProvider',
      30 => 'Zizaco\\Entrust\\EntrustServiceProvider',
      31 => 'Yajra\\Datatables\\DatatablesServiceProvider',
      32 => 'Collective\\Html\\HtmlServiceProvider',
      33 => 'Intervention\\Image\\ImageServiceProvider',
      34 => 'ConsoleTVs\\Charts\\ChartsServiceProvider',
      35 => 'App\\Providers\\AppServiceProvider',
      36 => 'App\\Providers\\AuthServiceProvider',
      37 => 'App\\Providers\\EventServiceProvider',
      38 => 'App\\Providers\\RouteServiceProvider',
      39 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      40 => 'Softon\\Indipay\\IndipayServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Entrust' => 'Zizaco\\Entrust\\EntrustFacade',
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'DNS1D' => 'Milon\\Barcode\\Facades\\DNS1DFacade',
      'DNS2D' => 'Milon\\Barcode\\Facades\\DNS2DFacade',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'SMS' => 'SimpleSoftwareIO\\SMS\\Facades\\SMS',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Zipper' => 'Chumper\\Zipper\\Zipper',
      'Indipay' => 'Softon\\Indipay\\Facades\\Indipay',
      'Share' => 'Chencha\\Share\\ShareFacade',
      'Newsletter' => 'Spatie\\Newsletter\\NewsletterFacade',
      'ImageSettings' => 'App\\ImageSettings',
      'Charts' => 'ConsoleTVs\\Charts\\Facades\\Charts',
      'Theme' => 'Shipu\\Themevel\\Facades\\Theme',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
        'table' => 'users',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'null',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '233456',
        'secret' => 'yoursecret',
        'app_id' => 'yourid',
        'options' => 
        array (
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'array',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'cart' => 
  array (
    'tax' => '0',
    'database' => 
    array (
      'connection' => NULL,
      'table' => 'shoppingcart',
    ),
    'destroy_on_logout' => false,
    'format' => 
    array (
      'decimals' => 2,
      'decimal_point' => '.',
      'thousand_seperator' => ',',
    ),
  ),
  'compile' => 
  array (
    'files' => 
    array (
    ),
    'providers' => 
    array (
    ),
  ),
  'database' => 
  array (
    'fetch' => 5,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'jsvudyngum',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'jsvudyngum',
        'username' => 'jsvudyngum',
        'password' => 'sAAeDXE3kq',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'jsvudyngum',
        'username' => 'jsvudyngum',
        'password' => 'sAAeDXE3kq',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
    ),
    'fractal' => 
    array (
      'includes' => 'include',
      'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
    ),
    'script_template' => 'datatables::script',
    'index_column' => 'DT_Row_Index',
    'namespace' => 
    array (
      'base' => 'DataTables',
      'model' => '',
    ),
    'pdf_generator' => 'excel',
    'snappy' => 
    array (
      'options' => 
      array (
        'no-outline' => true,
        'margin-left' => '0',
        'margin-right' => '0',
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
      ),
      'orientation' => 'landscape',
    ),
  ),
  'entrust' => 
  array (
    'role' => 'App\\Role',
    'roles_table' => 'roles',
    'permission' => 'App\\Permission',
    'permissions_table' => 'permissions',
    'permission_role_table' => 'permission_role',
    'role_user_table' => 'role_user',
    'user_foreign_key' => 'user_id',
    'role_foreign_key' => 'role_id',
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/app/public',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'indipay' => 
  array (
    'gateway' => 'PayUMoney',
    'testMode' => true,
    'ccavenue' => 
    array (
      'merchantId' => '',
      'accessCode' => '',
      'workingKey' => '',
      'redirectUrl' => 'indipay/response',
      'cancelUrl' => 'indipay/response',
      'currency' => 'INR',
      'language' => 'EN',
    ),
    'payumoney' => 
    array (
      'merchantKey' => 'do3vAdBt',
      'salt' => 'O0nqoiMiY7',
      'workingKey' => '4941163',
      'successUrl' => 'indipay/response',
      'failureUrl' => 'indipay/response',
    ),
    'ebs' => 
    array (
      'account_id' => '',
      'secretKey' => '',
      'return_url' => 'indipay/response',
    ),
    'citrus' => 
    array (
      'vanityUrl' => '',
      'secretKey' => '',
      'returnUrl' => 'indipay/response',
      'notifyUrl' => 'indipay/response',
    ),
    'instamojo' => 
    array (
      'api_key' => '',
      'auth_token' => '',
      'redirectUrl' => 'indipay/response',
    ),
    'remove_csrf_check' => 
    array (
      0 => 'indipay/response',
      1 => 'cart/paypal/status-success',
      2 => 'coupon/apply',
    ),
  ),
  'laravel-newsletter' => 
  array (
    'apiKey' => NULL,
    'defaultListName' => 'subscribers',
    'lists' => 
    array (
      'subscribers' => 
      array (
        'id' => NULL,
      ),
    ),
    'ssl' => true,
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.elasticemail.com',
    'port' => '2525',
    'from' => 
    array (
      'address' => 'info@lerneasy.in',
      'name' => 'LERNEASY',
    ),
    'encryption' => NULL,
    'username' => '0115e874-4617-4a08-ad18-6400e4fac05c',
    'password' => '0115e874-4617-4a08-ad18-6400e4fac05c',
    'sendmail' => '/usr/sbin/sendmail -bs',
  ),
  'messenger' => 
  array (
    'user_model' => 'App\\User',
    'message_model' => 'Cmgmyr\\Messenger\\Models\\Message',
    'participant_model' => 'Cmgmyr\\Messenger\\Models\\Participant',
    'thread_model' => 'Cmgmyr\\Messenger\\Models\\Thread',
    'messages_table' => 'messenger_messages',
    'participants_table' => 'messenger_participants',
    'threads_table' => 'messenger_threads',
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
    'facebook' => 
    array (
      'client_id' => 'YourID',
      'client_secret' => 'YourSecret',
      'redirect' => 'http://yoursite.com/auth/facebook/callback',
    ),
    'google' => 
    array (
      'client_id' => 'yourid.apps.googleusercontent.com',
      'client_secret' => 'yoursecret',
      'redirect' => 'http://yoursite.com/auth/google/callback',
    ),
    'msg91' => 
    array (
      'key' => '313100AbUG4F9nkF9U5e1d885fP1',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
  ),
  'sms' => 
  array (
    'driver' => 'twilio',
    'from' => 'SenderNumber',
    'callfire' => 
    array (
      'app_login' => 'Your CallFire API Login',
      'app_password' => 'Your CallFire API Password',
    ),
    'eztexting' => 
    array (
      'username' => 'Your EZTexting Username',
      'password' => 'Your EZTexting Password',
    ),
    'flowroute' => 
    array (
      'access_key' => 'Your Flowroute Access Key',
      'secret_key' => 'Your Flowroute Secret Key',
    ),
    'infobip' => 
    array (
      'username' => 'Your Infobip Username',
      'password' => 'Your Infobip Password',
    ),
    'labsmobile' => 
    array (
      'client_id' => 'Your Labsmobile Client ID',
      'username' => 'Your Labsmobile Username',
      'password' => 'Your Labsmobile Password',
      'test' => false,
    ),
    'mozeo' => 
    array (
      'company_key' => 'Your Mozeo Company Key',
      'username' => 'Your Mozeo Username',
      'password' => 'Your Mozeo Password',
    ),
    'nexmo' => 
    array (
      'api_key' => 'NEXMO_KEY',
      'api_secret' => 'NEXMO_SECRET',
    ),
    'plivo' => 
    array (
      'auth_id' => 'PLIVO_AUTH_ID',
      'auth_token' => 'PLIVO_AUTH_TOKEN',
    ),
    'twilio' => 
    array (
      'account_sid' => 'ACe0898c754f6229fed174d23cb767cffc',
      'auth_token' => '79b1a4c70e5f83680cb967e7c1605164',
      'verify' => true,
    ),
    'zenvia' => 
    array (
      'account_key' => 'Your Zenvia account key',
      'passcode' => 'Your Zenvia Passcode',
      'call_back_option' => 'NONE',
    ),
    'sms77' => 
    array (
      'user' => 'Your SMS77 API Login Name',
      'api_key' => 'Your SMS77 API Password or Api Key',
      'debug' => '0',
    ),
    'justsend' => 
    array (
      'api_key' => 'Your JustSend API key',
    ),
  ),
  'theme' => 
  array (
    'active' => '',
    'theme_path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/Themes',
    'symlink' => true,
    'types' => 
    array (
      'enable' => false,
      'middleware' => 
      array (
        'example' => 'admin',
      ),
    ),
    'config' => 
    array (
      'name' => 'theme.json',
      'changelog' => 'changelog.yml',
    ),
    'folders' => 
    array (
      'assets' => 'assets',
      'views' => 'views',
      'lang' => 'lang',
      'lang/en' => 'lang/en',
      'css' => 'assets/css',
      'js' => 'assets/js',
      'img' => 'assets/img',
      'layouts' => 'views/layouts',
    ),
    'stubs' => 
    array (
      'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/vendor/shipu/themevel/src/Console/stubs',
      'files' => 
      array (
        'css' => 'assets/css/app.css',
        'layout' => 'views/layouts/master.blade.php',
        'page' => 'views/welcome.blade.php',
        'lang' => 'lang/en/content.php',
      ),
    ),
  ),
  'trustedproxy' => 
  array (
    'proxies' => 
    array (
      0 => '192.168.1.10',
    ),
    'headers' => 
    array (
      1 => 'FORWARDED',
      2 => 'X_FORWARDED_FOR',
      4 => 'X_FORWARDED_HOST',
      8 => 'X_FORWARDED_PROTO',
      16 => 'X_FORWARDED_PORT',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/resources/views',
    ),
    'compiled' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/storage/framework/views',
  ),
  'file-manager' => 
  array (
    'configRepository' => 'Alexusmai\\LaravelFileManager\\Services\\ConfigService\\DefaultConfigRepository',
    'aclRepository' => 'Alexusmai\\LaravelFileManager\\Services\\ACLService\\ConfigACLRepository',
    'diskList' => 
    array (
      0 => 'public',
    ),
    'leftDisk' => NULL,
    'rightDisk' => NULL,
    'leftPath' => NULL,
    'rightPath' => NULL,
    'cache' => NULL,
    'windowsConfig' => 2,
    'maxUploadFileSize' => NULL,
    'allowFileTypes' => 
    array (
    ),
    'hiddenFiles' => true,
    'middleware' => 
    array (
      0 => 'web',
    ),
    'acl' => false,
    'aclHideFromFM' => true,
    'aclStrategy' => 'blacklist',
    'aclRulesCache' => NULL,
    'aclRules' => 
    array (
      '' => 
      array (
      ),
      1 => 
      array (
      ),
    ),
  ),
  'charts' => 
  array (
    'default' => 
    array (
      'type' => 'line',
      'library' => 'material',
      'element_label' => 'Element',
      'empty_dataset_label' => 'No Data Set',
      'empty_dataset_value' => 0,
      'title' => 'My Cool Chart',
      'height' => 400,
      'width' => 0,
      'responsive' => false,
      'background_color' => 'inherit',
      'colors' => 
      array (
      ),
      'one_color' => false,
      'template' => 'material',
      'legend' => true,
      'x_axis_title' => false,
      'y_axis_title' => NULL,
      'loader' => 
      array (
        'active' => true,
        'duration' => 500,
        'color' => '#000000',
      ),
    ),
    'templates' => 
    array (
      'material' => 
      array (
        0 => '#2196F3',
        1 => '#F44336',
        2 => '#FFC107',
      ),
      'red-material' => 
      array (
        0 => '#B71C1C',
        1 => '#F44336',
        2 => '#E57373',
      ),
      'indigo-material' => 
      array (
        0 => '#1A237E',
        1 => '#3F51B5',
        2 => '#7986CB',
      ),
      'blue-material' => 
      array (
        0 => '#0D47A1',
        1 => '#2196F3',
        2 => '#64B5F6',
      ),
      'teal-material' => 
      array (
        0 => '#004D40',
        1 => '#009688',
        2 => '#4DB6AC',
      ),
      'green-material' => 
      array (
        0 => '#1B5E20',
        1 => '#4CAF50',
        2 => '#81C784',
      ),
      'yellow-material' => 
      array (
        0 => '#F57F17',
        1 => '#FFEB3B',
        2 => '#FFF176',
      ),
      'orange-material' => 
      array (
        0 => '#E65100',
        1 => '#FF9800',
        2 => '#FFB74D',
      ),
    ),
    'assets' => 
    array (
      'global' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js',
        ),
      ),
      'canvas-gauges' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.2/all/gauge.min.js',
        ),
      ),
      'chartist' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css',
        ),
      ),
      'chartjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js',
        ),
      ),
      'fusioncharts' => 
      array (
        'scripts' => 
        array (
          0 => 'https://static.fusioncharts.com/code/latest/fusioncharts.js',
          1 => 'https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js',
        ),
      ),
      'google' => 
      array (
        'scripts' => 
        array (
          0 => 'https://www.google.com/jsapi',
          1 => 'https://www.gstatic.com/charts/loader.js',
          2 => 'google.charts.load(\'current\', {\'packages\':[\'corechart\', \'gauge\', \'geochart\', \'bar\', \'line\']})',
        ),
      ),
      'highcharts' => 
      array (
        'styles' => 
        array (
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js',
          2 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/map.js',
          3 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/data.js',
          4 => 'https://code.highcharts.com/mapdata/custom/world.js',
        ),
      ),
      'justgage' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js',
        ),
      ),
      'morris' => 
      array (
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
        ),
      ),
      'plottablejs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.css',
        ),
      ),
      'progressbarjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js',
        ),
      ),
      'c3' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css',
        ),
      ),
    ),
  ),
  'themevel' => 
  array (
    'active' => '',
    'theme_path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/Themes',
    'symlink' => true,
    'types' => 
    array (
      'enable' => false,
      'middleware' => 
      array (
        'example' => 'admin',
      ),
    ),
    'config' => 
    array (
      'name' => 'theme.json',
      'changelog' => 'changelog.yml',
    ),
    'folders' => 
    array (
      'assets' => 'assets',
      'views' => 'views',
      'lang' => 'lang',
      'lang/en' => 'lang/en',
      'css' => 'assets/css',
      'js' => 'assets/js',
      'img' => 'assets/img',
      'layouts' => 'views/layouts',
    ),
    'stubs' => 
    array (
      'path' => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/vendor/shipu/themevel/src/Console/stubs',
      'files' => 
      array (
        'css' => 'assets/css/app.css',
        'layout' => 'views/layouts/master.blade.php',
        'page' => 'views/welcome.blade.php',
        'lang' => 'lang/en/content.php',
      ),
    ),
  ),
  'captcha' => 
  array (
    'secret' => '',
    'sitekey' => '',
    'options' => 
    array (
      'timeout' => 2.0,
    ),
  ),
  'imagecache' => 
  array (
    'route' => NULL,
    'paths' => 
    array (
      0 => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/public/upload',
      1 => '/mnt/BLOCKSTORAGE/home/775163.cloudwaysapps.com/jsvudyngum/public_html/public/images',
    ),
    'templates' => 
    array (
      'small' => 'Intervention\\Image\\Templates\\Small',
      'medium' => 'Intervention\\Image\\Templates\\Medium',
      'large' => 'Intervention\\Image\\Templates\\Large',
    ),
    'lifetime' => 43200,
  ),
  'tinker' => 
  array (
    'dont_alias' => 
    array (
    ),
  ),
);
