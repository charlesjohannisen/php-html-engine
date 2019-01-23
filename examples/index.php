<?
include( dirname(dirname(__FILE__))."/HTML.php" );
include( dirname(dirname(__FILE__))."/DOC.php" );
include( dirname(dirname(__FILE__))."/FRAGMENT.php" );

$document = new DOC( DOC::XHTML_1_STR );
$document->add_title( "example page" );
$document->add_description( "" );
$document->add_keywords( "" );

$document->add_meta( array('http-equiv'=>"Content-Type", 'content'=>"text/html; charset=UTF-8") );

$document->add_icon( "images/favicon.ico" );

$document->add_stylesheet( "css/style.css" );
$document->add_scriptfile( "javascript/jquery.js" );
$document->add_scriptfile( "javascript/general.js" );
$document->add_script( 'alert("hi!");' );

$frag = new FRAGMENT;
$frag ->div('{"id":"contents"}')
        ->div('{"id":"maintext"}')
          ->table(2)
            ->tr(3)
              ->td('{"id":"td1","width":300}',[3])->content("cell1")
              ->td('{"id":"td1","width":300}',[3])->content("cell2")
              ->td('{"id":"td1","width":300}',[3])->content("cell3")
            ->tr(4,[2])
              ->td('{"id":"td1","width":300}',[4])->content("cell4")
              ->td('{"id":"td1","width":300}',[4])->content("cell5")
              ->td('{"id":"td1","width":300}',[4])->content("cell6")
            ;

$document->add_element( $frag->render() );

print $document;
