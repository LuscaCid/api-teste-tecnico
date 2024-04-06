<?php

class DiskStorageProvider {

  public static function renameDir( string $filePath ) {
    rename( $filePath, __DIR__ ."uploads");
  }
  
} 