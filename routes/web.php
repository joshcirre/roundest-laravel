<?php

use Livewire\Volt\Volt;

Volt::route('/', 'index')->lazy();
Volt::route('/results', 'results')->lazy();
