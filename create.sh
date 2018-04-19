#!/bin/bash

# syntax goes like this:
# sudo create flexmls loader BVBR
# or
# sudo create archive BEACHES

# options:
# loader, archive

# $1  MLS template format or "archive" (or --help)
# $2 "loader" or archive name
# $3 MLS name

# -- help - simple help file
MLSNAME='mlsconfig/'$3
MLSCHECK=`find mlsconfig/ -name $3 -type d`

#supported MLS formats - as more are added, create new vars and add to case statement with pipe
F1='flexmls'

#current running setups (can't create new with this name, only archive)
declare -a SETUPS=(`ls ./mlsconfig`)


#didn't understand command
IDUNNO="Huh?\ncreate usage:\n\tcreate flexmls loader MRED\n\n
-or-\n\tcreate archive BVBR\n -or-\n\tcreate --help"

#help tag
HELP="--help"

#help tag result output
HELPFILE="MLS Loader create - load a new MLS based on templates\nUSAGE:\n
create [STYLE] loader [MLSNAME]\n\n-CURRENT SUPPORTED MLS FORMATS-\n
\033[34mconnectmls \033[0m/flexmls/\033[34m innovia/matrix/navica/paragon/rapattoni/retsiq\033[0m\n
\n-ACTIONS-\n\n - create (see above for usage)\n - archive ( create archive [MLSNAME] )\n
\t(use this to shut down an MLS)\n"

echo "first arg"
#parse inputs
case $1 in
"--help")
    echo -e $HELPFILE
    exit 1
    ;;
"archive")
    #need to check $2 for current setup name
    #echo "starting archive process for "$2"..."
    if [[ $MLSNAME == $MLSCHECK ]]; then
        #confirm, then should be good to archive
        echo "archiving "$2"..."
    else
        echo $2" is not a current MLS name. I can't archive an MLS that doesn't exist."
        exit 1
    fi
    ;;
$F1)
    #need to check rest of args
    echo "using flexmls template"
    ;;
*)
    echo -e $IDUNNO
    exit 1
    ;;
esac


echo "second arg"

#if [ $2 = "loader" ]; then
#    if [[ $MLSNAME == $MLSCHECK ]]; then
#        echo "sorry, that MLS name is already taken. Try a different name."
#        exit 1
#    else
#        echo "creating "$1" template for MLS "$3"..."
#    fi
#elif [ $1 = "archive"]; then
#    
#   else
#        #bad MLS name for archive
#        echo "If archiving, you must supply a current MLS."
#        exit 1
#    fi
#else
#    echo -e $IDUNNO
#    exit 1
#fi


#basic 'are you sure' confirmation for archives
#read -r -p "Are you sure? [y/N] " response
#case "$response" in
#    [yY][eE][sS]|[yY]) 
#        do_something
#        ;;
#    *)
#        do_something_else
#       ;;
#esac