#!/bin/bash

display_help()
{
    echo "evertracker PHP client generator for SOAP services"
    echo "Usage:"
    echo "  generate [options]"
    echo "Options (* are mandatory):"
    echo "* -w|--service-wsdl=<URL>"
    echo "  the URL of the SOAP service's WSDL file"
    echo "* -n|--namespace=<namespace>"
    echo "  the namespace of the generated PHP client classes"
    echo "* -o|--output-folder=<folder path>"
    echo "  the folder path where the generated PHP client classes will be written"
    echo "  -a|--authentication=<authentication type; 0=SOAP_AUTHENTICATION_BASIC; 1=SOAP_AUTHENTICATION_DIGEST>"
    echo "  the default authentication type; the client service class will have this option as default"
    echo "  -u|--username=<username>"
    echo "  it's the default username that authenticates with basic http authentication, for the SOAP client"
    echo "  -p|--password=<password>"
    echo "  it's the default password to authenticate the user with the basic http authentication, for the SOAP client"
    echo "  -c|--connection-timeout=<seconds>"
    echo "  it's the default connection timeout in seconds for the SOAP client"
    echo "  -h|--help"
    echo "  displays this help"
}

cd "$(dirname "$0")"

for i in "$@"
do
    case $i in
        -w=*|--service-wsdl=*)
        SERVICE_WSDL="${i#*=}"
        shift # past argument=value
        ;;
        -n=*|--namespace=*)
        NAMESPACE="${i#*=}"
        shift # past argument=value
        ;;
        -o=*|--output-folder=*)
        OUTPUT_FOLDER="${i#*=}"
        shift # past argument=value
        ;;
        -a=*|--authentication=*)
        AUTHENTICATION_TYPE="${i#*=}"
        shift # past argument=value
        ;;
        -u=*|--username=*)
        USERNAME="${i#*=}"
        shift # past argument=value
        ;;
        -p=*|--password=*)
        PASSWORD="${i#*=}"
        shift # past argument=value
        ;;
        -c=*|--connection-timeout=*)
        CONNECTION_TIMEOUT="${i#*=}"
        shift # past argument=value
        ;;
        -h|--help)
        shift # past argument=value
        display_help
        exit
        ;;
        --default)
        DEFAULT=YES
        shift # past argument with no value
        ;;
        *)
              # unknown option
        ;;
    esac
done
if [[ (-z "$SERVICE_WSDL") || (-z "$NAMESPACE") || (-z "$OUTPUT_FOLDER") ]]; then
    display_help
else

    echo "WSDL: ${SERVICE_WSDL}"
    echo "Namespace: ${NAMESPACE}"
    echo "Output folder: ${OUTPUT_FOLDER}"
    PHP_PARAMS="serviceWsdlUrl=${SERVICE_WSDL}"
    PHP_PARAMS="${PHP_PARAMS}&clientClassesNamespace=${NAMESPACE}"
    PHP_PARAMS="${PHP_PARAMS}&clientClassesOutputFolder=${OUTPUT_FOLDER}"
    if [ -n "$AUTHENTICATION_TYPE" ]; then
        PHP_PARAMS="${PHP_PARAMS}&authenticationType=${AUTHENTICATION_TYPE}"
    fi
    if [ -n "$USERNAME" ]; then
        PHP_PARAMS="${PHP_PARAMS}&username=${USERNAME}"
    fi
    if [ -n "$PASSWORD" ]; then
        PHP_PARAMS="${PHP_PARAMS}&password=${PASSWORD}"
    fi
    if [ -n "$CONNECTION_TIMEOUT" ]; then
        PHP_PARAMS="${PHP_PARAMS}&connectionTimeout=${CONNECTION_TIMEOUT}"
    fi
    php index.php $PHP_PARAMS

fi
