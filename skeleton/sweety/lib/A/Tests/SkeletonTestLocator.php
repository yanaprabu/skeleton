<?php

require_once 'Sweety/TestLocator.php';

class A_Tests_SkeletonTestLocator implements Sweety_TestLocator
{
  
  public function getTests($dirs = array())
  {
    $tests = array();
    foreach ($dirs as $dir)
    {
      $handle = opendir($dir);
      while (false !== $file = readdir($handle))
      {
        if (preg_match('/^(.*?)_test\.php$/D', $file, $matches))
        {
          $tests[] = $matches[1] . 'Test';
        }
      }
      closedir($handle);
    }
    sort($tests);
    return $tests;
  }
  
  public function includeTest($testCase)
  {
    $file = substr($testCase, 0, -4) . '_test.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $dir)
    {
      if (is_file($dir . '/' . $file))
      {
        require_once $dir . '/' . $file;
        return true;
      }
    }
    
    return false;
  }
  
}
