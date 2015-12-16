<?php
/*
Displays everything from <div id="container" class="clearfix"> closing tag to the closing html tag
*/

$wipOptions = get_option( 'wipOptions' );
$campaignProgress = get_post_meta( $post->ID, 'campaignprogress', true );
?>

			</div><!-- #content -->

			<div id="footer">

				<hr />

        <div id="local">
          <a href="<?php bloginfo( 'url' ); ?>/"><?php bloginfo( 'name' ); ?></a>,
          <?php
            if( $wipOptions['footerInfo'] )
							echo esc_html( $wipOptions['footerInfo'] ); // The theme options "Footer" > "Physical Address" field
            if( $wipOptions['footerPhone'] )
							echo ', <a href="tel:+1' . esc_attr( $wipOptions['footerPhone'] ) . '">' . esc_html( $wipOptions['footerPhone'] ) . '</a>'; // The theme options "Footer" > "Phone Number" field
            if( $wipOptions['footerContact'] )
							echo ', <a href="' . esc_url( $wipOptions['footerContact'] ) . '">Contact Us</a>'; // The theme options "Footer" > "Contact..." field
          ?>
        </div><!-- #local -->

				<div id="sitemap"><!-- Not sure what to do for this -->

          <div class="sitemap_column">
            <h4>Academics</h4>
            <h5 class="trigger">Departments</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://afs.wsu.edu">Agricultural and Food Systems</a></li>
                  <li><a href="http://ansci.wsu.edu">Animal Sciences</a></li>
                  <li><a href="http://amdt.wsu.edu">Apparel, Merchandising, Design and Textiles</a></li>
                  <li><a href="http://www.bsyse.wsu.edu/">Biological Systems Engineering</a></li>
                  <li><a href="http://css.wsu.edu">Crop and Soil Sciences</a></li>
                  <li><a href="http://ses.wsu.edu">School of Economic Sciences</a></li>
                  <li><a href="http://entomology.wsu.edu">Entomology</a></li>
                  <li><a href="http://horticulture.wsu.edu">Horticulture</a></li>
                  <li><a href="http://hd.wsu.edu">Human Development</a></li>
                  <li><a href="http://id.wsu.edu">Interior Design</a></li>
                  <li><a href="http://ips.wsu.edu">Integrated Plant Sciences</a></li>
                  <li><a href="http://nrs.wsu.edu">Natural Resource Sciences</a></li>
                  <li><a href="http://plantpath.wsu.edu/">Plant Pathology</a></li>
                  <li><a href="http://sfs.wsu.edu">School of Food Science</a></li>
                  <li><a href="http://environment.wsu.edu/">School of the Environment</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Majors</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7982">Agricultural and Food Business Economics</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7870">Agricultural Biotechnology</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7527">Agricultural Education</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7843">Agricultural Technology and Production Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=8088">Agriculture and Food Security</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7699">Animal Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=41279">Apparel Design, Merchandising, and Textiles</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1781">Economic Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1609">Environmental Science</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7658">Field Crop Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1615">Food Science</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1652">Fruit and Vegetable Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1656">Human Development</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7627">Interior Design</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1668">Landscape Architecture</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7871">Landscape Design and Implementation</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7659">Landscape, Nursery, and Greenhouse Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1698">Natural Resource Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7529">Organic Agriculture</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7595">Turfgrass Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1653">Viticulture and Enology</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7840">Wildlife Ecology</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Minors</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1840">Aging/Gerontology</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7524">Agribusiness Economics</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7699">Animal Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7718">Crop Science</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1781">Economic Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1609">Environmental Science</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1798">Horticulture</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1656">Human Development</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1698">Natural Resource Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7737">Rangeland Ecology and Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7739">Soil Science</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7744">Sustainable Development</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Certificates</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=205">Adolescence</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1840">Aging/Gerontology</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7527">Agricultural Education</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7747">Early Childhood Development</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1655">Family and Consumer Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7825">Family Studies</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7529">Organic Agriculture</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Specializations</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=41272">Animal Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7539">Apparel Design</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1598">Business Economics</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1599">Economic Analysis and Policy</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1596">Economics Graduate School Preparation</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1608">Environmental and Resource Economics</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1655">Family and Consumer Sciences</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1595">Financial Markets</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1652">Fruit and Vegetable Management</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1597">International Trade and Development</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7540">Merchandising</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=1699">Natural Resource Policy/Pre-law</a></li>
                  <li><a href="http://admission.wsu.edu/academics/fos/Public/field.castle?id=7641">Wetland/Aquatic Resources</a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="sitemap_column">
            <h4>Extension and Outreach</h4>
            <h5 class="trigger">Agriculture &amp; Food Systems</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://weather.wsu.edu/">AgWeatherNet</a></li>
                  <li><a href="http://variety.wsu.edu/">Cereals Variety Testing</a></li>
                  <li><a href="http://das.wsu.edu/">Decision Aid System</a></li>
                  <li><a href="http://farm-mgmt.wsu.edu/">Farm Management Resources</a></li>
                  <li><a href="http://ird.wsu.edu/">International Research and Development</a></li>
                  <li><a href="http://pep.wsu.edu/">Pesticide Education Program</a></li>
                  <li><a href="http://cru66.cahe.wsu.edu/LabelTolerance.html">Pesticide Information Center Online (PICOL)</a></li>
                  <li><a href="http://potatoes.wsu.edu/">Potato Information &amp; Exchange</a></li>
                  <li><a href="http://smallfarms.wsu.edu/">Small Farms Team</a></li>
                  <li><a href="http://soilfertility.wsu.edu/">Soil Fertility Program</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/sweetcherryresearch/Pages/default.aspx">Sweet Cherry Research</a></li>
                  <li><a href="http://vetextension.wsu.edu/">Veterinary Medicine Extension</a></li>
                  <li><a href="http://wine.wsu.edu/">Viticulture and Enology</a></li>
                  <li><a href="http://public.wsu.edu/creamery/">WSU Creamery and Ferdinand&rsquo;s</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Nutrition &amp; Healthy Families</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://nutrition.wsu.edu/diabetes/">Diabetes Awareness Education</a></li>
                  <li><a href="http://extension.wsu.edu/farmersmarket/Pages/default.aspx">Farmers Market Nutrition Program</a></li>
                  <li><a href="http://nutrition.wsu.edu/foodsense/">Food $ense</a></li>
                  <li><a href="http://germcity.wsu.edu/">Germ City</a></li>
                  <li><a href="http://nutrition.wsu.edu/nen/">Nutrition Education Network</a></li>
                  <li><a href="http://parenting.wsu.edu/">Parenting and Family Education</a></li>
                  <li><a href="http://extension.wsu.edu/ahec/pages/default.aspx">WSU Extension Area Health Education Center of Eastern Washington</a></li>
                  <li><a href="http://foodsafety.wsu.edu/">WSU Extension Food Safety</a></li>
                  <li><a href="http://nutrition.wsu.edu/">WSU Extension Nutrition Education</a></li>
                  <li><a href="http://sfp.wsu.edu/">WSU Extension Strengthening Families Program</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Environment &amp; Natural Resources</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://www.beachwatchers.wsu.edu/regional/index.php">Beach Watchers</a></li>
                  <li><a href="http://www.cereo.wsu.edu/">Center for Environmental Research, Education &amp; Outreach (CEREO)</a></li>
                  <li><a href="http://csanr.wsu.edu/">Center for Sustaining Agriculture and Natural Resources (CSANR)</a></li>
                  <li><a href="http://county.wsu.edu/chelan-douglas/nrs/Colockum/Pages/default.aspx">Colockum Natural Resource Center</a></li>
                  <li><a href="http://www.cmec.wsu.edu/">Composite Materials &amp; Engineering Center</a></li>
                  <li><a href="http://ird.wsu.edu/">International Research and Development</a></li>
                  <li><a href="http://www.puyallup.wsu.edu/stormwater/">Low Impact Development (LID) Stormwater Research Program</a></li>
                  <li><a href="http://extension.wsu.edu/forestry/">WSU Extension Forestry and Wildlife</a></li>
                  <li><a href="http://snohomish.wsu.edu/forestry/">WSU Extension Puget Sound Forest Stewardship</a></li>
                  <li><a href="http://raingarden.wsu.edu/">WSU Extension Puget Sound Rain Gardens</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Communities &amp; Economic Development</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://ird.wsu.edu/">International Research and Development</a></li>
                  <li><a href="http://horizons.wsu.edu/">The Horizons Community Development Project</a></li>
                  <li><a href="http://dgss.wsu.edu/Overview.html">Division of Governmental Studies and Services</a></li>
                  <li><a href="http://www.impact.wsu.edu/">The IMPACT Center</a></li>
                  <li><a href="http://ruckelshauscenter.wsu.edu/">William D. Ruckelshaus Center</a></li>
                  <li><a href="http://ext.wsu.edu/diversity/training/index.html">Navigating Difference – Cultural Competency Training</a></li>
                  <li><a href="http://extecon.wsu.edu/pages/Regional_Economics">Community and Regional Economics</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">4-H Youth Development</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://4h.wsu.edu">WSU Extension 4-H Youth Development Program</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Gardening</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://ipm.wsu.edu/">WSU Extension Integrated Pest Management</a></li>
                  <li><a href="http://mastergardener.wsu.edu/">WSU Extension Master Gardener Program</a></li>
                  <li><a href="http://www.puyallup.wsu.edu/plantclinic/">WSU Plant &amp; Insect Diagnostic Laboratory</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Energy &amp; Climate</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://css.wsu.edu/biofuels/">Biofuels Cropping Systems</a></li>
                  <li><a href="http://www.cereo.wsu.edu/">Center for Environmental Research, Educatiom &amp; Outreach (CEREO)</a></li>
                  <li><a href="http://csanr.wsu.edu/CFF/">Climate Friendly Farming</a></li>
                  <li><a href="http://www.energy.wsu.edu/">WSU Extension Energy Program</a></li>
                  <li><a href="http://snohomish.wsu.edu/carbonmasters/">WSU Extension Sustainable	Community Stewards (formerly Carbon Masters)</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">WSU Extension County Offices</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://lincoln-adams.wsu.edu/">Adams County</a></li>
                  <li><a href="http://www.asotin.wsu.edu/">Asotin County</a></li>
                  <li><a href="http://benton-franklin.wsu.edu/">Benton County</a></li>
                  <li><a href="http://www.ncw.wsu.edu/">Chelan County</a></li>
                  <li><a href="http://clallam.wsu.edu/">Clallam County</a></li>
                  <li><a href="http://clark.wsu.edu/">Clark County</a></li>
                  <li><a href="http://columbia.wsu.edu/">Columbia County</a></li>
                  <li><a href="http://cowlitz.wsu.edu/">Cowlitz County</a></li>
                  <li><a href="http://www.ncw.wsu.edu/">Douglas County</a></li>
                  <li><a href="http://ferry.wsu.edu/">Ferry County</a></li>
                  <li><a href="http://benton-franklin.wsu.edu/">Franklin County</a></li>
                  <li><a href="http://garfield.wsu.edu/">Garfield County</a></li>
                  <li><a href="http://www.grant-adams.wsu.edu/">Grant County</a></li>
                  <li><a href="http://graysharbor.wsu.edu/">Grays Harbor County</a></li>
                  <li><a href="http://www.island.wsu.edu/">Island County</a></li>
                  <li><a href="http://county.wsu.edu/jefferson/Pages/default.aspx">Jefferson County</a></li>
                  <li><a href="http://king.wsu.edu/">King County</a></li>
                  <li><a href="http://kitsap.wsu.edu/">Kitsap County</a></li>
                  <li><a href="http://county.wsu.edu/KITTITAS/Pages/default.aspx">Kittitas County</a></li>
                  <li><a href="http://www.klickitat.wsu.edu/">Klickitat County</a></li>
                  <li><a href="http://lewis.wsu.edu/">Lewis County</a></li>
                  <li><a href="http://lincoln-adams.wsu.edu/">Lincoln County</a></li>
                  <li><a href="http://mason.wsu.edu/">Mason County</a></li>
                  <li><a href="http://okanogan.wsu.edu/">Okanogan County</a></li>
                  <li><a href="http://pacific.wsu.edu/">Pacific County</a></li>
                  <li><a href="http://pendoreille.wsu.edu/">Pend Oreille County</a></li>
                  <li><a href="http://www.pierce.wsu.edu/">Pierce County</a></li>
                  <li><a href="http://sanjuan.wsu.edu/">San Juan County</a></li>
                  <li><a href="http://skagit.wsu.edu/">Skagit County</a></li>
                  <li><a href="http://skamania.wsu.edu/">Skamania County</a></li>
                  <li><a href="http://snohomish.wsu.edu/">Snohomish County</a></li>
                  <li><a href="http://www.spokane-county.wsu.edu/">Spokane County</a></li>
                  <li><a href="http://stevens.wsu.edu/">Stevens County</a></li>
                  <li><a href="http://thurston.wsu.edu/">Thurston County</a></li>
                  <li><a href="http://wahkiakum.wsu.edu/">Wahkiakum County</a></li>
                  <li><a href="http://www.wallawalla.wsu.edu/">Walla Walla County</a></li>
                  <li><a href="http://whatcom.wsu.edu/">Whatcom County</a></li>
                  <li><a href="http://www.whitman.wsu.edu/">Whitman County</a></li>
                  <li><a href="http://county.wsu.edu/yakima/pages/default.aspx">Yakima County</a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="sitemap_column">
            <h4>Around the State</h4>
            <h5 class="trigger">WSU Extension County Offices</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://lincoln-adams.wsu.edu/">Adams County</a></li>
                  <li><a href="http://www.asotin.wsu.edu/">Asotin County</a></li>
                  <li><a href="http://benton-franklin.wsu.edu/">Benton County</a></li>
                  <li><a href="http://www.ncw.wsu.edu/">Chelan County</a></li>
                  <li><a href="http://clallam.wsu.edu/">Clallam County</a></li>
                  <li><a href="http://clark.wsu.edu/">Clark County</a></li>
                  <li><a href="http://columbia.wsu.edu/">Columbia County</a></li>
                  <li><a href="http://cowlitz.wsu.edu/">Cowlitz County</a></li>
                  <li><a href="http://www.ncw.wsu.edu/">Douglas County</a></li>
                  <li><a href="http://ferry.wsu.edu/">Ferry County</a></li>
                  <li><a href="http://benton-franklin.wsu.edu/">Franklin County</a></li>
                  <li><a href="http://garfield.wsu.edu/">Garfield County</a></li>
                  <li><a href="http://www.grant-adams.wsu.edu/">Grant County</a></li>
                  <li><a href="http://graysharbor.wsu.edu/">Grays Harbor County</a></li>
                  <li><a href="http://www.island.wsu.edu/">Island County</a></li>
                  <li><a href="http://county.wsu.edu/jefferson/Pages/default.aspx">Jefferson County</a></li>
                  <li><a href="http://king.wsu.edu/">King County</a></li>
                  <li><a href="http://kitsap.wsu.edu/">Kitsap County</a></li>
                  <li><a href="http://county.wsu.edu/KITTITAS/Pages/default.aspx">Kittitas County</a></li>
                  <li><a href="http://www.klickitat.wsu.edu/">Klickitat County</a></li>
                  <li><a href="http://lewis.wsu.edu/">Lewis County</a></li>
                  <li><a href="http://lincoln-adams.wsu.edu/">Lincoln County</a></li>
                  <li><a href="http://mason.wsu.edu/">Mason County</a></li>
                  <li><a href="http://okanogan.wsu.edu/">Okanogan County</a></li>
                  <li><a href="http://pacific.wsu.edu/">Pacific County</a></li>
                  <li><a href="http://pendoreille.wsu.edu/">Pend Oreille County</a></li>
                  <li><a href="http://www.pierce.wsu.edu/">Pierce County</a></li>
                  <li><a href="http://sanjuan.wsu.edu/">San Juan County</a></li>
                  <li><a href="http://skagit.wsu.edu/">Skagit County</a></li>
                  <li><a href="http://skamania.wsu.edu/">Skamania County</a></li>
                  <li><a href="http://snohomish.wsu.edu/">Snohomish County</a></li>
                  <li><a href="http://www.spokane-county.wsu.edu/">Spokane County</a></li>
                  <li><a href="http://stevens.wsu.edu/">Stevens County</a></li>
                  <li><a href="http://thurston.wsu.edu/">Thurston County</a></li>
                  <li><a href="http://wahkiakum.wsu.edu/">Wahkiakum County</a></li>
                  <li><a href="http://www.wallawalla.wsu.edu/">Walla Walla County</a></li>
                  <li><a href="http://whatcom.wsu.edu/">Whatcom County</a></li>
                  <li><a href="http://www.whitman.wsu.edu/">Whitman County</a></li>
                  <li><a href="http://county.wsu.edu/yakima/pages/default.aspx">Yakima County</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Research &amp; Extension Centers</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://longbeach.wsu.edu/">WSU Long Beach Research and Extension Unit</a></li>
                  <li><a href="http://mtvernon.wsu.edu/">WSU Mount Vernon Northwestern Washington Research and Extension Center</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/prosser/Pages/default.aspx">WSU Prosser Irrigated Agriculture Research and Extension Center</a></li>
                  <li><a href="http://www.puyallup.wsu.edu/">WSU Puyallup Research and Extension Center</a></li>
                  <li><a href="http://www.tfrec.wsu.edu/">WSU Wenatchee Tree Fruit Research and Extension Center</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Farms &amp; Facilites</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://weather.wsu.edu/">AgWeatherNet</a></li>
                  <li><a href="http://www.ansci.wsu.edu/facilities/facilities.aspx">Animal ScienceFacilities</a></li>
                  <li><a href="http://css.wsu.edu/facilities/cook/">Cook Agronomy Farm</a></li>
                  <li><a href="http://www.puyallup.wsu.edu/turf/">Goss Turfgrass Research Farm</a></li>
                  <li><a href="http://lindstation.wsu.edu/">Lind Dryland Research Staton</a></li>
                  <li><a href="http://cahnrs.wsu.edu/wp-content/themes/design-draft/">Organic ResearchSite - Puyallup</a></li>
                  <li><a href="http://css.wsu.edu/organicfarm">Organic Teaching Farm - Puyallup</a></li>
                  <li><a href="http://css.wsu.edu/overview/facilities/index.htm#Palouse">PalouseConservation FIeld Station</a></li>
                  <li><a href="http://pgf.arc.wsu.edu/">Plant Growth Facility</a></li>
                  <li><a href="http://turf.wsu.edu/research.htm">Turfgrass and Agronomy ResearchCenter</a></li>
                  <li><a href="http://css.wsu.edu/facilities/spillman/">Spillman Agronomy Farm</a></li>
                  <li><a href="http://css.wsu.edu/facilities/dhlab/">Wheat Double Haploid Facility</a></li>
                  <li><a href="http://wilkefarm.wsu.edu/">Wilke Research and Extension Farm</a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="sitemap_column">
            <h4>Misc</h4>
            <h5 class="trigger">Administrative Offices</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://cahnrs.wsu.edu/">Academic Programs for CAHNRS</a></li>
                  <li><a href="http://cahnrs.wsu.edu/">Agricultural Research Center</a></li>
                  <li><a href="http://cahnrs.wsu.edu/">Alumni &amp; Friends</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/bc/Pages/default.aspx">Business Centers</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/bc/fsclark/Pages/default.aspx">Business Center - Food Science – Clark Hall</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/bc/hulbert/Pages/default.aspx">Busines Center - Hulbert Hall</a></li>
                  <li><a href="http://cahnrs-cms.wsu.edu/bc/johnson/Pages/default.aspx">Business Center - Johnson Hall 201</a></li>
                  <li><a href="http://bfo.cahe.wsu.edu/personnel/NewHires/">Business &amp; Finance Office</a></li>
                  <li><a href="http://cwr.wsu.edu/">Computing Resources</a></li>
                  <li><a href="http://cahnrs.wsu.edu/">Dean of the WSU College of Agricultural, Human, and Natural Resource Scienes</a></li>
                  <li><a href="http://news.cahnrs.wsu.edu/">Marketing, News, and Educational Communications</a></li>
                  <li><a href="http://ogrd.wsu.edu/">OGRD</a></li>
                  <li><a href="http://ext.wsu.edu">WSU Extension</a></li>
                </ul>
              </div>
            </div>
            <h5 class="trigger">Other Resources</h5>
            <div class="toggle_container">
              <div class="block">
                <ul>
                  <li><a href="http://connect.wsu.edu/">WSU Connect: Microsoft Outlook web access</a></li>
                  <li><a href="http://ams.wsu.edu/Videoconference/Splash.aspx">AMS Video Conference System</a></li>
                  <li><a href="http://sbs.wsu.edu/fmic/">Franceschi Microscopy &amp; Imaging (Electron Microscope Center)</a></li>
                  <li><a href="http://www.sees.wsu.edu/Geolab/index.html">Geoanalytical Laboratory</a></li>
                  <li><a href="http://www.wsulibs.wsu.edu/">WSU Libraries</a></li>
                  <li><a href="http://identity.wsu.edu/">WSU Graphic Identity</a></li>
                  <li><a href="http://identity.wsu.edu/logo/ext-sig-extension.aspx">Extension Graphic Identity Program</a></li>
                </ul>
              </div>
            </div>
          </div>

				</div><!-- #site-map -->

        <div id="wsu">
          <a href="http://publishing.wsu.edu/copyright/WSU.html">&copy; <?php echo date('Y'); ?></a>
          <a href="http://www.wsu.edu">Washington State University</a> |
          <a href="http://access.wsu.edu/">Accessibility</a> |
          <a href="http://policies.wsu.edu/">Policies</a> |
          <a href="http://publishing.wsu.edu/copyright/WSU.html">Copyright</a> |
          <?php wp_loginout(); ?>
        </div><!-- #wsu -->
      
			</div><!-- #footer -->

		</div><!-- #wrapper.clearfix -->

		<?php
			// For the A&F site - campaign progress meter
			if( isset( $campaignProgress ) && $campaignProgress != '' ) {
				?>
          <script type="text/javascript">
						var dollars = <?php echo esc_js( $campaignProgress ); // @todo maybe cast as (int) ? ?>;
						var cpProp = dollars * 183;
						var cpPercent = ~~( cpProp / 236.5 );
						var cpDollars = ~~( dollars + 1 );
						jQuery(document).ready(function($) {
							$('#cougmeter_progress').animate({ height: cpPercent }, 2500, function() {});
							$('#arrow').animate({ marginBottom: cpPercent }, 2500, function() {});
							$({ countNum: $('#dollaramount').text() }).animate({ countNum: cpDollars }, { duration: 2500, easing:'linear', step: function() { $('#dollaramount').text( Math.floor( this.countNum ) ); }, complete: function() {} });
						});
          </script>
        <?php
      }

      // If either of the "Show AddThis... " options are selected, add the Javascript for it
      if( $wipOptions['postShare'] || $wipOptions['pageShare'] || $wipOptions['tbShareLink'] ) {
        ?>
          <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=<?php if( $wipOptions['addThis'] ) echo urlencode( stripslashes( $wipOptions['addThis'] ) ); ?>"></script>
          <script type="text/javascript">
            var addthis_config = {
              services_compact: 'email, facebook, twitter, google, more',
              services_exclude: 'print',
              ui_click: true,
              ui_508_compliant: true,
              data_track_clickback: true
            }
          </script>
        <?php
      }
      
      // If the "Google Analytics ID" field has been filled in, add the Javascript for it
      if( $wipOptions['googleAnalytics'] ) {
        ?>
        <script>
          var _gaq=[['_setAccount','<?php echo esc_js( $wipOptions['googleAnalytics'] ); ?>'],['_trackPageview']];
          (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
          g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
          s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
        <?php
      }
      
      wp_footer();
    ?>
	</body>
</html>