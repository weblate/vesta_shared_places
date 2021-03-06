<?php

namespace Cissee\Webtrees\Module\SharedPlaces;

use Fisharebest\Webtrees\GedcomTag;
use Fisharebest\Webtrees\I18N;
use Vesta\ControlPanelUtils\Model\ControlPanelCheckbox;
use Vesta\ControlPanelUtils\Model\ControlPanelFactRestriction;
use Vesta\ControlPanelUtils\Model\ControlPanelPreferences;
use Vesta\ControlPanelUtils\Model\ControlPanelRadioButton;
use Vesta\ControlPanelUtils\Model\ControlPanelRadioButtons;
use Vesta\ControlPanelUtils\Model\ControlPanelRange;
use Vesta\ControlPanelUtils\Model\ControlPanelSection;
use Vesta\ControlPanelUtils\Model\ControlPanelSubsection;

trait SharedPlacesModuleTrait {

  protected function getMainTitle() {
    return I18N::translate('Vesta Shared Places');
  }

  public function getShortDescription() {
    return
            I18N::translate('A module providing support for shared places.');
  }

  protected function getFullDescription() {
    $link1 = '<a href="https://github.com/vesta-webtrees-2-custom-modules/vesta_shared_places">'.I18N::translate('Main Readme.').'</a>';
    $link2 = '<a href="https://github.com/vesta-webtrees-2-custom-modules/vesta_common/blob/master/docs/LocationData.md">'.I18N::translate('Vesta location data management overview.').'</a>';

    $description = array();    
    //TODO add link to https://genealogy.net/GEDCOM/
    $description[] = /* I18N: Module Configuration */I18N::translate('A module supporting shared places as level 0 GEDCOM objects, on the basis of the GEDCOM-L Addendum to the GEDCOM 5.5.1 specification. Shared places may contain e.g. map coordinates, notes and media objects. The module displays this data for all matching places via the extended \'Facts and events\' tab. It may also be used to manage GOV ids, in combination with the Gov4Webtrees module.');
    $description[] = /* I18N: Module Configuration */I18N::translate('Requires the \'%1$s Vesta Common\' module, and the \'%1$s Vesta Facts and events\' module.', $this->getVestaSymbol());
    $description[] = /* I18N: Module Configuration */I18N::translate('Provides location data to other custom modules.');
    $description[] = $link1 . ' ' . $link2;
    return $description;
  }

  protected function createPrefs() {
    $generalSub = array();
    $generalSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Displayed title'),
            array(
                /*new ControlPanelCheckbox(
                I18N::translate('Include the %1$s symbol in the module title', $this->getVestaSymbol()),
                null,
                'VESTA',
                '1'),*/
        new ControlPanelCheckbox(
                /* I18N: Module Configuration */I18N::translate('Include the %1$s symbol in the list menu entry', $this->getVestaSymbol()),
                null,
                'VESTA_LIST',
                '1')));
    
    $link = '<a href="https://github.com/vesta-webtrees-2-custom-modules/vesta_shared_places">'.I18N::translate('Readme').'</a>';
    
    $generalSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Shared place structure'),
            array(new ControlPanelCheckbox(
                /* I18N: Module Configuration */I18N::translate('Use hierarchical shared places'),
                /* I18N: Module Configuration */I18N::translate('If checked, relations between shared places are modelled via an explicit hierarchy, where shared places have XREFs to higher-level shared places, as described in the specification.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('Note that this also affects the way shared places are created, and the way they are mapped to places.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('In particular, hierarchical shared places do not have names with comma-separated name parts.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('See %1$s for details.', $link) . ' ' .
                /* I18N: Module Configuration */I18N::translate('There is a data fix available which may be used to convert existing shared places.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('When unchecked, the former approach is used, in which hierarchies are only hinted at by using shared place names with comma-separated name parts.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('It is strongly recommended to switch to hierarchical shared places.'),
                'USE_HIERARCHY',
                '1')));
    
    $generalSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Linking of shared places to places'),
            array(
        new ControlPanelCheckbox(
                /* I18N: Module Configuration */I18N::translate('Additionally link shared places via name'),
                /* I18N: Module Configuration */I18N::translate('According to the GEDCOM-L Addendum, shared places are referenced via XREFs, just like shared notes etc. ') .
                /* I18N: Module Configuration */I18N::translate('It is now recommended to use XREFs, as this improves performance and flexibility. There is a data fix available which may be used to add XREFs. ') .
                /* I18N: Module Configuration */I18N::translate('However, you can still check this option and link shared places via the place name itself. In this case, links are established internally by searching for a shared place with any name matching case-insensitively.') . ' ' .
                /* I18N: Module Configuration */I18N::translate('If you are using hierarchical shared places, a place with the name "A, B, C" is mapped to a shared place "A" with a higher-level shared place that maps to "B, C".'),
                'INDIRECT_LINKS',
                '0'),
        new ControlPanelRange(
                /* I18N: Module Configuration */I18N::translate('... and fall back to n parent levels'),
                /* I18N: Module Configuration */I18N::translate('When the preceding option is checked, and no matching shared place is found, fall back to n parent places (so that e.g. for n=2 a place "A, B, C" would also match shared places that match "B, C" and "C")'),
                0,
                5,
                'INDIRECT_LINKS_PARENT_LEVELS',
                0)));
    
    $factsSub = array();
    $factsSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('All shared place facts'),
            array(     
        ControlPanelFactRestriction::createWithFacts(
                SharedPlacesModuleTrait::getPicklistFactsLoc(),
                /* I18N: Module Configuration */I18N::translate('This is the list of GEDCOM facts that your users can add to shared places. You can modify this list by removing or adding fact names as necessary. Fact names that appear in this list must not also appear in the “Unique shared place facts” list.'),
                '_LOC_FACTS_ADD',
                'NAME,_LOC:TYPE,NOTE,SHARED_NOTE,SOUR,_LOC:_LOC')));
    $factsSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Unique shared place facts'),
            array(     
        ControlPanelFactRestriction::createWithFacts(
                SharedPlacesModuleTrait::getPicklistFactsLoc(),
                /* I18N: Module Configuration */I18N::translate('This is the list of GEDCOM facts that your users can only add once to shared places. For example, if NAME is in this list, users will not be able to add more than one NAME record to a shared place. Fact names that appear in this list must not also appear in the “All shared place facts” list.'),
                '_LOC_FACTS_UNIQUE',
                'MAP,_GOV')));
    
    //really not that useful currently
    /*
    $factsSub[] = new ControlPanelSubsection(
            I18N::translate('Facts for new shared places'),
            array(     
        ControlPanelFactRestriction::createWithFacts(
                SharedPlacesModuleTrait::getPicklistFactsLoc(true),
                I18N::translate('This is the list of GEDCOM facts that will be shown when adding a new shared place.'),
                '_LOC_FACTS_REQUIRED',
                '')));
    */
    
    $factsSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Quick shared place facts'),
            array(
        ControlPanelFactRestriction::createWithFacts(
                SharedPlacesModuleTrait::getPicklistFactsLoc(),
                /* I18N: Module Configuration */I18N::translate('This is the list of GEDCOM facts that your users can add to shared places. You can modify this list by removing or adding fact names as necessary. Fact names that appear in this list must not also appear in the “Unique shared place facts” list. '),
                '_LOC_FACTS_QUICK',
                'NAME,_LOC:_LOC,MAP,NOTE,SHARED_NOTE,_GOV')));
    
    $factsAndEventsSub = array();
    $factsAndEventsSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Displayed data'),
            array(     
        new ControlPanelCheckbox(
                /* I18N: Module Configuration */I18N::translate('Restrict to specific facts and events'),
                /* I18N: Module Configuration */I18N::translate('If this option is checked, shared place data is only displayed for the following facts and events. ') .
                /* I18N: Module Configuration */I18N::translate('In particular if both lists are empty, no additional facts and events of this kind will be shown.'),
                'RESTRICTED',
                '0'),
        ControlPanelFactRestriction::createWithIndividualFacts(
                /* I18N: Module Configuration */I18N::translate('Restrict to this list of GEDCOM individual facts and events. You can modify this list by removing or adding fact and event names, even custom ones, as necessary.'),
                'RESTRICTED_INDI',
                'BIRT,OCCU,RESI,DEAT'),
        ControlPanelFactRestriction::createWithFamilyFacts(
                /* I18N: Module Configuration */I18N::translate('Restrict to this list of GEDCOM family facts and events. You can modify this list by removing or adding fact and event names, even custom ones, as necessary.'),
                'RESTRICTED_FAM',
                'MARR')));

    $factsAndEventsSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Automatically expand shared place data'),
            array(new ControlPanelRadioButtons(
                false,
                array(
            new ControlPanelRadioButton(
                    ' './* I18N: Module Configuration */I18N::translate('no'),
                    null,
                    '0'),
            new ControlPanelRadioButton(
                    /* I18N: Module Configuration */I18N::translate('yes, but only the first occurrence of the shared place'),
                    /* I18N: Module Configuration */I18N::translate('Note that the first occurrence may be within a toggleable, currently hidden fact or event (such as an event of a close relative). This will probably be improved in future versions of the module.'),
                    '1'),
            new ControlPanelRadioButton(
                    /* I18N: Module Configuration */I18N::translate('yes'),
                    null,
                    '2')),
                null,
                'EXPAND',
                '1')));

    $listSub = array();
    $listSub[] = new ControlPanelSubsection(
            /* I18N: Module Configuration */I18N::translate('Displayed data'),
            array(new ControlPanelCheckbox(
                /* I18N: Module Configuration */I18N::translate('Show link counts for shared places list'),
                /* I18N: Module Configuration */I18N::translate('Determining the link counts (linked individual/families) is expensive when assigning shared places via name, and therefore causes delays when the shared places list is displayed. It\'s recommended to only select this option if places are assigned via XREFs.'),
                'LINK_COUNTS',
                '1')));

    $sections = array();
    $sections[] = new ControlPanelSection(
            /* I18N: Module Configuration */I18N::translate('General'),
            null,
            $generalSub);
    $sections[] = new ControlPanelSection(
            /* I18N: Module Configuration */I18N::translate('Facts for shared place records'),
            null,
            $factsSub);
    $sections[] = new ControlPanelSection(
            /* I18N: Module Configuration */I18N::translate('Facts and Events Tab Settings'),
            null,
            $factsAndEventsSub);
    $sections[] = new ControlPanelSection(
            /* I18N: Module Configuration */I18N::translate('Shared places list'),
            null,
            $listSub);

    return new ControlPanelPreferences($sections);
  }

  public static function getPicklistFactsLoc(bool $forRequired = false): array {
    $tags = [
        "NAME",
        "_LOC:TYPE",
        "NOTE",
        "SHARED_NOTE",
        "SOUR",
        "_LOC:_LOC",
        "MAP",
        "_GOV"];
    
    if ($forRequired) {
      //others are redundant, tricky, or anyway TBI
      $tags = [
        "NOTE"];
      
        //"SHARED_NOTE" problematic (potential modal within modal)
    }
    
    $facts = [];
    foreach ($tags as $tag) {
        $facts[$tag] = GedcomTag::getLabel($tag);
    }
    uasort($facts, '\Fisharebest\Webtrees\I18N::strcasecmp');

    return $facts;
  }
}
