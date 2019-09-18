<?php 

switch($TGBot->cbdata_text){
    # Messaggio iniziale
    case '/ticket':
        $TGBot->cb_ticket();
        break;
    case '/paysafe':
        $TGBot->cb_paysafe();
        break;
    case '/info':
        $TGBot->cb_info();
        break;

    case '/annulla':
        $TGBot->cb_annulla();
        break;
    case '/indietro':
        $TGBot->cb_indietro();
        break;

    # Ticket - cheaters
    case '/reportcheaters':
        $TGBot->cb_report_cheaters();
        break;

    case '/reportcheatersvideo':
        $TGBot->cb_report_cheaters_video();
        break;
    case '/reportcheatersonline':
        $TGBot->cb_report_cheaters_online();
        break;

    case '/reportfazioni':
        $TGBot->cb_report_fazioni();
        break;
    case '/reportminiprison':
        $TGBot->cb_report_miniprison();
        break;
    case '/reportfarmpvp':
        $TGBot->cb_report_farmpvp();
        break;
    case '/reporthub':
        $TGBot->cb_report_hub();
        break;

    # Ticket - bugs
    case '/reportbug':
        $TGBot->cb_report_bugs();
        break;

    # Ticket - parla con staff
    case '/parla':
        $TGBot->cb_parla();
        break;
    case '/parlaprocedi':
        $TGBot->cb_parla_procedi();
        break;

}