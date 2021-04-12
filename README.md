# Flashforge Finder php API
This is an unofficial php API for the Flashforge Finder inspired from https://github.com/01F0/flashforge-finder-api/. It allows to request status information from the printer like temperature, progress, etc. It may also work for other Flashforge printer models but has only been tested on the Finder.

## Warning
Use this API at your own risk. It only performs reading operations but it is unofficial and may of course have bugs.

## Installation

Coming soon:
This package is installed via [Composer](https://getcomposer.org/).

Run the following to require the package
```sh
    composer require acgy/flashforge-finder-php-api
```

## Usage
Instanciate the printer with IP address and port (port is optionnal, specify it only if you're not using the 9988 default port).

Example output:

```php
$printer = new \acgy\Printer('192.168.0.5');
print_r($printer->get('progress'));
/*
Array
(
    [1] => 543
    [2] => 1000
)
*/
```

If you want to display the full text returned by the printer, use the verbose mode

### Available commands (in verbose mode)
```php
echo $printer->get('control', true);
/*
CMD M601 Received.
Control Success.
ok
*/

echo $printer->get('info', true);
/*
CMD M115 Received.
Machine Type: Flashforge Finder
Machine Name: My 3D Printer
Firmware: ***********
SN: *******
X: 140 Y: 140 Z: 140
Tool Count:1
ok
*/

echo $printer->get('position', true);
/*
CMD M114 Received.
X:0 Y:0 Z:0 A:0 B:0
ok
*/

echo $printer->get('temperature', true);
/*
CMD M105 Received.
T0:210 /210 B:0/0
ok
*/

echo $printer->get('progress', true);
/*
CMD M27 Received.
SD printing byte 543/1000
ok
*/

echo $printer->get('status', true);
/*
MD M119 Received.
Endstop: X-max:1 Y-max:0 Z-max:1
MachineStatus: READY
MoveMode: READY
Status: S:1 L:0 J:0 F:0
ok
*/
```