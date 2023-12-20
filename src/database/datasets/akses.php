<?php

/**
 * Rules:
 * <verb> <noun/resource>
 * 
 * Where:
 * verb: index, view, create, update, delete, approve
 */
return [
    'columns'   => ['nama'],
    'imports'  => [
        // barang masuk
        ['index barang masuk'],
        ['view barang masuk'],
        ['create barang masuk'],
        ['update barang masuk'],
        ['delete barang masuk'],
        // persediaan
        ['index persediaan'],
        ['view persediaan'],
        // konversi stok
        ['index konversi stok'],
        ['create konversi stok'],
        ['update konversi stok'],
        ['delete konversi stok'],
        // user
        ['index user'],
        ['view barang masuk'],
        ['create user'],
        ['update user'],
        ['delete user'],
    ],
];