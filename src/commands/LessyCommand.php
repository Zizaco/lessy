<?php namespace Zizaco\Lessy;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LessyCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lessy:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compiles less files';

    /**
     * Illuminate application instance.
     * 
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create a new Lessy command instance.
     *
     * @param  Basset\Basset  $basset
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $lessy = new Lessy($this->app);
        $this->line("\n<comment>Lessy ".LESSY_VERSION."</comment> <info>Compiling files...</info>");
        $lessy->compileLessFiles( true );
        
        if ($this->app['config']->get ( 'lessy::auto_minify' )) {
        	$lessy->minify();
        }
        
        $this->info("Done\n");
    }

}
