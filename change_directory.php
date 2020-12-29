<?php

class Path
{
    const ROOT_PATH = '/';
    const DIR_SEPARATOR = '/';

    public $currentPath;

    public function __construct(string $path)
    {
        $this->validateRootPath($path);
        $this->setPath($path);
    }

    /**
     * Set path to variable.
     *
     * @param string $path
     *
     * @return void
     */
    private function setPath(string $path)
    {
        if (strpos($path, '..') !== false) {
            $path = $this->resolveRelativePath($this->normalize($path));
        }

        $this->currentPath = $path;
    }

    /**
     * Validate that root path has correct format.
     *
     * @param string $value
     *
     * @throws Exception
     *
     * @return void
     */
    public function validateRootPath(string $value): void
    {
        if (!preg_match('/^(\/)+([a-zA-Z\/]{1,})+((\.\.\/)+)|(\/)+([a-zA-Z\/]{1,})$/', $value)
            && !preg_match('/^(\/)+([a-zA-Z\/]{1,})+((\.\.)+)$/', $value)) {
            throw new Exception('Path should started from '.self::ROOT_PATH.' and consists of a-zA-Z, .. and /');
        }
    }

    /**
     * Validate that path has correct format.
     *
     * @param string $value
     *
     * @throws Exception
     *
     * @return void
     */
    public function validatePath(string $value): void
    {
        if (!preg_match('/^[a-zA-Z\/\.]{1,}$/', $value)) {
            throw new Exception('Path should follow pattern a-zA-Z, .. and /');
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function normalize(string $value): string
    {
        $value = preg_replace('#/+#', self::DIR_SEPARATOR, $value);

        if (strlen($value) > 1 && substr($value, -1) == '/') {
            $value = rtrim($value, self::DIR_SEPARATOR);
        }

        return $value;
    }

    /**
     * Naviagate to specific directory.
     *
     * @param string $newPath
     *
     * @throws Exception
     *
     * @return Path
     */
    public function cd(string $newPath): Path
    {
        $this->validatePath($newPath);

		$path = $this->currentPath.self::DIR_SEPARATOR.$newPath;

        if (strpos($newPath, self::DIR_SEPARATOR) === 0) {
            $path = $newPath;
        }
        
        $this->setPath($path);

        return $this;
    }

    /**
     * Resolve relative path parts to absolute path.
     *
     * @param string $relativePath
     *
     * @return string
     */
    private function resolveRelativePath(string $relativePath): string
    {
        $result = [];
        $pathData = array_filter(explode(self::DIR_SEPARATOR, $relativePath));

        foreach ($pathData as $path) {
            if ($path === '..') {
                array_pop($result);
            } else {
                $result[] = $path;
            }
        }

        $result = implode(self::DIR_SEPARATOR, $result);
        $position = strpos($result, self::DIR_SEPARATOR);

        //
        if ($position != 0 || $position === false) {
            $result = self::ROOT_PATH.$result;
        }

        return $result;
    }
}

try {
    $path = new Path('/a/b/c/d');
    echo $path->cd('../x')->currentPath; // /a/b/c/x
    echo "\r\n";
    $path = new Path('/a/b/c/d/..');
    echo $path->cd('../df')->currentPath; // /a/b/x
    echo "\r\n";
    $path = new Path('/a/b/c/d/..');
    echo $path->cd('/df')->currentPath; // /df
    echo "\r\n";
    $path = new Path('/a/b/c/d');
    echo $path->cd('/../x')->currentPath; // /x
} catch (Exception $e) {
    echo $e->getMessage();
}
