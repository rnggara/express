<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mig {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName($name)
    {

        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }

        $pathModel = $this->getSourceFilePathModel();

        $this->makeDirectory(dirname($pathModel));

        $contentsModel = $this->getSourceFileModel();

        if (!$this->files->exists($pathModel)) {
            $this->files->put($pathModel, $contentsModel);
            $this->info("File : {$pathModel} created");
        } else {
            $this->info("File : {$pathModel} already exits");
        }
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath()
    {
        return __DIR__ . '/../../../stubs/migrate.stub';
    }

    public function getClassName($name){
        return "Create".str_replace(" ", "", ucwords(str_replace("_", " ", Pluralizer::singular($name))))."Table";
    }

    /**
    **
    * Map the stub variables present in stub to its value
    *
    * @return array
    *
    */
    public function getStubVariables()
    {
        return [
            'CLASS_NAME'        => $this->getClassName($this->argument("name")),
            'name'              => strtolower($this->argument("name"))
        ];
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }


    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return base_path('database/migrations') .'/' .date("Y_m_d_His")."_create_".strtolower($this->getSingularClassName($this->argument('name'))) . "_table" . '.php';
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPathModel()
    {
        return __DIR__ . '/../../../stubs/soft.model.stub';
    }

    /**
    **
    * Map the stub variables present in stub to its value
    *
    * @return array
    *
    */
    public function getStubVariablesModel()
    {
        return [
            'CLASS_NAME'        => $this->getSingularClassName($this->argument('name')),
            'name'              => strtolower($this->argument("name"))
        ];
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFileModel()
    {
        return $this->getStubContentsModel($this->getStubPathModel(), $this->getStubVariablesModel());
    }


    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContentsModel($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePathModel()
    {
        return base_path('app/Models') .'/' .$this->getSingularClassName($this->argument('name')) . '.php';
    }
}
