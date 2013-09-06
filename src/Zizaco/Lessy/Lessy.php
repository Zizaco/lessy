<?php namespace Zizaco\Lessy;

use lessc;

class Lessy
{

    /**
     * Less compiler
     *
     * @var lessc
     */
    public $lessc;

    /**
     * Laravel application
     *
     * @var Illuminate\Foundation\Application
     */
    public $_app;

    /**
     * Create a new Lessy instance.
     *
     * @param  Illuminate\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->_app = $app;
        $this->lessc = new lessc;
    }

    public function compileTree($origin, $destination)
    {
        $this->_app['config']->set('lessy::origin', $origin);
        $this->_app['config']->set('lessy::destination', $destination);

        $this->compileLessFiles();
    }

    /**
     * Compiles the less files
     *
     * @param  bool  $verbose
     * @return void
     */
    public function compileLessFiles( $verbose = false )
    {
        $root =        $this->_app['path'].'/';
        $origin =      $this->_app['config']->get('lessy::origin');
        $destination = $this->_app['config']->get('lessy::destination');

        if( empty($origin) )
            $origin = 'less';

        if( empty($destination) )
            $destination = '../public/assets/css';

        if( $verbose )
        {
            print_r( 'LESS files: <app>/'.$origin."\n" );
            print_r( 'Output to:  <app>/'.$destination."\n\n" );
        }

        $origin =      $root.$origin;
        $destination = $root.$destination;

        if ( ! is_dir($destination) )
            mkdir($destination, 0775, true);

        $tree = $this->compileRecursive( $origin.'/', $destination.'/', '', $verbose );
    }

    /**
     * Recursive file compilation
     *
     * @param  string  $origin
     * @param  string  $destiny
     * @param  string  $offset
     * @param  bool  $verbose
     * @return array
     */
    protected function compileRecursive( $origin, $destiny, $offset = '', $verbose = false )
    {
        $tree = array();

        if( ! is_dir($origin.$offset) )
        {
            return $tree;
        }

        $dir = scandir( $origin.$offset );

        foreach ( $dir as $filename )
        {
            if ( is_dir( $origin.$offset.$filename ) and $filename != '.' and $filename != '..')
            {
                if ( ! file_exists( $destiny.$offset.$filename ) )
                {
                    mkdir( $destiny.$offset.$filename );
                }

                // Recursive call
                $tree[$filename] = $this->compileRecursive( $origin, $destiny, $offset.$filename.'/', $verbose );
            }
            elseif ( is_file( $origin.$offset.$filename ))
            {
                if ( substr($filename,-5) == '.less' or substr($filename,-4) == '.css' )
                {
                    $tree[] = $filename;

                    if( $verbose )
                    {
                        print_r( $offset.$filename."\n" );
                    }

                    // Compile file
                    $this->lessc->checkedCompile(
                        $origin.$offset.$filename,
                        $destiny.$offset.substr($filename,0,strrpos($filename,'.',-1)).'.css'
                    );
                }
                else
                {
                    $in = $origin.$offset.$filename;
                    $out = $destiny.$offset.$filename;

                    if( $verbose )
                    {
                        print_r( $offset.$filename."\n" );
                    }

                    // Copy any assets that the css/less may use
                    if (!is_file($out) || filemtime($in) > filemtime($out)) {
                        copy(
                            $in,
                            $out
                        );
                    }
                }
            }
        }

        return $tree;
    }

    /**
     * Compiles one less file
     *
     * @param  bool  $verbose
     * @return void
     */
    public function compileSingleFile( $filename, $verbose = false )
    {
        $root =        $this->_app['path'].'/';
        $origin =      $this->_app['config']->get('lessy::origin');
        $destination = $this->_app['config']->get('lessy::destination');

        if( empty($origin) )
            $origin = 'less';

        if( empty($destination) )
            $destination = '../public/assets/css';

        $origin .= '/'.$filename

        if( $verbose )
        {
            print_r( 'LESS file: <app>/'.$origin."\n" );
            print_r( 'Output to:  <app>/'.$destination."\n\n" );
        }

        $origin =      $root.$origin;
        $destination = $root.$destination;

        if ( ! is_dir($destination) )
            mkdir($destination, 0775, true);

        // Compile file
        $this->lessc->checkedCompile(
            $origin,
            $destiny.'/'.substr($filename,0,strrpos($filename,'.',-1)).'.css'
        );
    }

}
