<?php


/**
 * Converts a NIACS code into the correspinding human readable designation.
 * @param string $NIACS_CODE The NIACS code to be converted.
 * @filesource http://www.census.gov/epcd/naics07/
 */

function convert_NIACS( $NIACS_CODE ) {
	
	switch ( $NIACS_CODE ) {
		case "":
		return "";
		break;
		case "11":
		return "Agriculture, Forestry, Fishing and Hunting";
		break;
		case "111":
		return "Crop Production";
		break;
		case "1111":
		return "Oilseed and Grain Farming";
		break;
		case "11111":
		return "Soybean Farming";
		break;
		case "111110":
		return "Soybean Farming";
		break;
		case "11112":
		return "Oilseed (except Soybean) Farming";
		break;
		case "111120":
		return "Oilseed (except Soybean) Farming";
		break;
		case "11113":
		return "Dry Pea and Bean Farming";
		break;
		case "111130":
		return "Dry Pea and Bean Farming";
		break;
		case "11114":
		return "Wheat Farming";
		break;
		case "111140":
		return "Wheat Farming";
		break;
		case "11115":
		return "Corn Farming";
		break;
		case "111150":
		return "Corn Farming";
		break;
		case "11116":
		return "Rice Farming";
		break;
		case "111160":
		return "Rice Farming";
		break;
		case "11119":
		return "Other Grain Farming";
		break;
		case "111191":
		return "Oilseed and Grain Combination Farming";
		break;
		case "111199":
		return "All Other Grain Farming";
		break;
		case "1112":
		return "Vegetable and Melon Farming";
		break;
		case "11121":
		return "Vegetable and Melon Farming";
		break;
		case "111211":
		return "Potato Farming";
		break;
		case "111219":
		return "Other Vegetable (except Potato) and Melon Farming";
		break;
		case "1113":
		return "Fruit and Tree Nut Farming";
		break;
		case "11131":
		return "Orange Groves";
		break;
		case "111310":
		return "Orange Groves";
		break;
		case "11132":
		return "Citrus (except Orange) Groves";
		break;
		case "111320":
		return "Citrus (except Orange) Groves";
		break;
		case "11133":
		return "Noncitrus Fruit and Tree Nut Farming";
		break;
		case "111331":
		return "Apple Orchards";
		break;
		case "111332":
		return "Grape Vineyards";
		break;
		case "111333":
		return "Strawberry Farming";
		break;
		case "111334":
		return "Berry (except Strawberry) Farming";
		break;
		case "111335":
		return "Tree Nut Farming";
		break;
		case "111336":
		return "Fruit and Tree Nut Combination Farming";
		break;
		case "111339":
		return "Other Noncitrus Fruit Farming";
		break;
		case "1114":
		return "Greenhouse, Nursery, and Floriculture Production";
		break;
		case "11141":
		return "Food Crops Grown Under Cover";
		break;
		case "111411":
		return "Mushroom Production";
		break;
		case "111419":
		return "Other Food Crops Grown Under Cover";
		break;
		case "11142":
		return "Nursery and Floriculture Production";
		break;
		case "111421":
		return "Nursery and Tree Production";
		break;
		case "111422":
		return "Floriculture Production";
		break;
		case "1119":
		return "Other Crop Farming";
		break;
		case "11191":
		return "Tobacco Farming";
		break;
		case "111910":
		return "Tobacco Farming";
		break;
		case "11192":
		return "Cotton Farming";
		break;
		case "111920":
		return "Cotton Farming";
		break;
		case "11193":
		return "Sugarcane Farming";
		break;
		case "111930":
		return "Sugarcane Farming";
		break;
		case "11194":
		return "Hay Farming";
		break;
		case "111940":
		return "Hay Farming";
		break;
		case "11199":
		return "All Other Crop Farming";
		break;
		case "111991":
		return "Sugar Beet Farming";
		break;
		case "111992":
		return "Peanut Farming";
		break;
		case "111998":
		return "All Other Miscellaneous Crop Farming";
		break;
		case "112":
		return "Animal Production";
		break;
		case "1121":
		return "Cattle Ranching and Farming";
		break;
		case "11211":
		return "Beef Cattle Ranching and Farming, including Feedlots";
		break;
		case "112111":
		return "Beef Cattle Ranching and Farming";
		break;
		case "112112":
		return "Cattle Feedlots";
		break;
		case "11212":
		return "Dairy Cattle and Milk Production";
		break;
		case "112120":
		return "Dairy Cattle and Milk Production";
		break;
		case "11213":
		return "Dual-Purpose Cattle Ranching and Farming";
		break;
		case "112130":
		return "Dual-Purpose Cattle Ranching and Farming";
		break;
		case "1122":
		return "Hog and Pig Farming";
		break;
		case "11221":
		return "Hog and Pig Farming";
		break;
		case "112210":
		return "Hog and Pig Farming";
		break;
		case "1123":
		return "Poultry and Egg Production";
		break;
		case "11231":
		return "Chicken Egg Production";
		break;
		case "112310":
		return "Chicken Egg Production";
		break;
		case "11232":
		return "Broilers and Other Meat Type Chicken Production";
		break;
		case "112320":
		return "Broilers and Other Meat Type Chicken Production";
		break;
		case "11233":
		return "Turkey Production";
		break;
		case "112330":
		return "Turkey Production";
		break;
		case "11234":
		return "Poultry Hatcheries";
		break;
		case "112340":
		return "Poultry Hatcheries";
		break;
		case "11239":
		return "Other Poultry Production";
		break;
		case "112390":
		return "Other Poultry Production";
		break;
		case "1124":
		return "Sheep and Goat Farming";
		break;
		case "11241":
		return "Sheep Farming";
		break;
		case "112410":
		return "Sheep Farming";
		break;
		case "11242":
		return "Goat Farming";
		break;
		case "112420":
		return "Goat Farming";
		break;
		case "1125":
		return "Aquaculture";
		break;
		case "11251":
		return "Aquaculture";
		break;
		case "112511":
		return "Finfish Farming and Fish Hatcheries";
		break;
		case "112512":
		return "Shellfish Farming";
		break;
		case "112519":
		return "Other Aquaculture";
		break;
		case "1129":
		return "Other Animal Production";
		break;
		case "11291":
		return "Apiculture";
		break;
		case "112910":
		return "Apiculture";
		break;
		case "11292":
		return "Horses and Other Equine Production";
		break;
		case "112920":
		return "Horses and Other Equine Production";
		break;
		case "11293":
		return "Fur-Bearing Animal and Rabbit Production";
		break;
		case "112930":
		return "Fur-Bearing Animal and Rabbit Production";
		break;
		case "11299":
		return "All Other Animal Production";
		break;
		case "112990":
		return "All Other Animal Production";
		break;
		case "113":
		return "Forestry and Logging";
		break;
		case "1131":
		return "Timber Tract Operations";
		break;
		case "11311":
		return "Timber Tract Operations";
		break;
		case "113110":
		return "Timber Tract Operations";
		break;
		case "1132":
		return "Forest Nurseries and Gathering of Forest Products";
		break;
		case "11321":
		return "Forest Nurseries and Gathering of Forest Products";
		break;
		case "113210":
		return "Forest Nurseries and Gathering of Forest Products";
		break;
		case "1133":
		return "Logging";
		break;
		case "11331":
		return "Logging";
		break;
		case "113310":
		return "Logging";
		break;
		case "114":
		return "Fishing, Hunting and Trapping";
		break;
		case "1141":
		return "Fishing";
		break;
		case "11411":
		return "Fishing";
		break;
		case "114111":
		return "Finfish Fishing";
		break;
		case "114112":
		return "Shellfish Fishing";
		break;
		case "114119":
		return "Other Marine Fishing";
		break;
		case "1142":
		return "Hunting and Trapping";
		break;
		case "11421":
		return "Hunting and Trapping";
		break;
		case "114210":
		return "Hunting and Trapping";
		break;
		case "115":
		return "Support Activities for Agriculture and Forestry";
		break;
		case "1151":
		return "Support Activities for Crop Production";
		break;
		case "11511":
		return "Support Activities for Crop Production";
		break;
		case "115111":
		return "Cotton Ginning";
		break;
		case "115112":
		return "Soil Preparation, Planting, and Cultivating";
		break;
		case "115113":
		return "Crop Harvesting, Primarily by Machine";
		break;
		case "115114":
		return "Postharvest Crop Activities (except Cotton Ginning)";
		break;
		case "115115":
		return "Farm Labor Contractors and Crew Leaders";
		break;
		case "115116":
		return "Farm Management Services";
		break;
		case "1152":
		return "Support Activities for Animal Production";
		break;
		case "11521":
		return "Support Activities for Animal Production";
		break;
		case "115210":
		return "Support Activities for Animal Production";
		break;
		case "1153":
		return "Support Activities for Forestry";
		break;
		case "11531":
		return "Support Activities for Forestry";
		break;
		case "115310":
		return "Support Activities for Forestry";
		break;
		case "21":
		return "Mining, Quarrying, and Oil and Gas Extraction";
		break;
		case "211":
		return "Oil and Gas Extraction";
		break;
		case "2111":
		return "Oil and Gas Extraction";
		break;
		case "21111":
		return "Oil and Gas Extraction";
		break;
		case "211111":
		return "Crude Petroleum and Natural Gas Extraction";
		break;
		case "211112":
		return "Natural Gas Liquid Extraction";
		break;
		case "212":
		return "Mining (except Oil and Gas)";
		break;
		case "2121":
		return "Coal Mining";
		break;
		case "21211":
		return "Coal Mining";
		break;
		case "212111":
		return "Bituminous Coal and Lignite Surface Mining";
		break;
		case "212112":
		return "Bituminous Coal Underground Mining";
		break;
		case "212113":
		return "Anthracite Mining";
		break;
		case "2122":
		return "Metal Ore Mining";
		break;
		case "21221":
		return "Iron Ore Mining";
		break;
		case "212210":
		return "Iron Ore Mining";
		break;
		case "21222":
		return "Gold Ore and Silver Ore Mining";
		break;
		case "212221":
		return "Gold Ore Mining";
		break;
		case "212222":
		return "Silver Ore Mining";
		break;
		case "21223":
		return "Copper, Nickel, Lead, and Zinc Mining";
		break;
		case "212231":
		return "Lead Ore and Zinc Ore Mining";
		break;
		case "212234":
		return "Copper Ore and Nickel Ore Mining";
		break;
		case "21229":
		return "Other Metal Ore Mining";
		break;
		case "212291":
		return "Uranium-Radium-Vanadium Ore Mining";
		break;
		case "212299":
		return "All Other Metal Ore Mining";
		break;
		case "2123":
		return "Nonmetallic Mineral Mining and Quarrying";
		break;
		case "21231":
		return "Stone Mining and Quarrying";
		break;
		case "212311":
		return "Dimension Stone Mining and Quarrying";
		break;
		case "212312":
		return "Crushed and Broken Limestone Mining and Quarrying";
		break;
		case "212313":
		return "Crushed and Broken Granite Mining and Quarrying";
		break;
		case "212319":
		return "Other Crushed and Broken Stone Mining and Quarrying";
		break;
		case "21232":
		return "Sand, Gravel, Clay, and Ceramic and Refractory Minerals Mining and Quarrying";
		break;
		case "212321":
		return "Construction Sand and Gravel Mining";
		break;
		case "212322":
		return "Industrial Sand Mining";
		break;
		case "212324":
		return "Kaolin and Ball Clay Mining";
		break;
		case "212325":
		return "Clay and Ceramic and Refractory Minerals Mining";
		break;
		case "21239":
		return "Other Nonmetallic Mineral Mining and Quarrying";
		break;
		case "212391":
		return "Potash, Soda, and Borate Mineral Mining";
		break;
		case "212392":
		return "Phosphate Rock Mining";
		break;
		case "212393":
		return "Other Chemical and Fertilizer Mineral Mining";
		break;
		case "212399":
		return "All Other Nonmetallic Mineral Mining";
		break;
		case "213":
		return "Support Activities for Mining";
		break;
		case "2131":
		return "Support Activities for Mining";
		break;
		case "21311":
		return "Support Activities for Mining";
		break;
		case "213111":
		return "Drilling Oil and Gas Wells";
		break;
		case "213112":
		return "Support Activities for Oil and Gas Operations";
		break;
		case "213113":
		return "Support Activities for Coal Mining";
		break;
		case "213114":
		return "Support Activities for Metal Mining";
		break;
		case "213115":
		return "Support Activities for Nonmetallic Minerals (except Fuels) Mining";
		break;
		case "22":
		return "Utilities";
		break;
		case "221":
		return "Utilities";
		break;
		case "2211":
		return "Electric Power Generation, Transmission and Distribution";
		break;
		case "22111":
		return "Electric Power Generation";
		break;
		case "221111":
		return "Hydroelectric Power Generation";
		break;
		case "221112":
		return "Fossil Fuel Electric Power Generation";
		break;
		case "221113":
		return "Nuclear Electric Power Generation";
		break;
		case "221119":
		return "Other Electric Power Generation";
		break;
		case "22112":
		return "Electric Power Transmission, Control, and Distribution";
		break;
		case "221121":
		return "Electric Bulk Power Transmission and Control";
		break;
		case "221122":
		return "Electric Power Distribution";
		break;
		case "2212":
		return "Natural Gas Distribution";
		break;
		case "22121":
		return "Natural Gas Distribution";
		break;
		case "221210":
		return "Natural Gas Distribution";
		break;
		case "2213":
		return "Water, Sewage and Other Systems";
		break;
		case "22131":
		return "Water Supply and Irrigation Systems";
		break;
		case "221310":
		return "Water Supply and Irrigation Systems";
		break;
		case "22132":
		return "Sewage Treatment Facilities";
		break;
		case "221320":
		return "Sewage Treatment Facilities";
		break;
		case "22133":
		return "Steam and Air-Conditioning Supply";
		break;
		case "221330":
		return "Steam and Air-Conditioning Supply";
		break;
		case "23":
		return "Construction";
		break;
		case "236":
		return "Construction of Buildings";
		break;
		case "2361":
		return "Residential Building Construction";
		break;
		case "23611":
		return "Residential Building Construction";
		break;
		case "236115":
		return "New Single-Family Housing Construction (except Operative Builders)";
		break;
		case "236116":
		return "New Multifamily Housing Construction (except Operative Builders)";
		break;
		case "236117":
		return "New Housing Operative Builders";
		break;
		case "236118":
		return "Residential Remodelers";
		break;
		case "2362":
		return "Nonresidential Building Construction";
		break;
		case "23621":
		return "Industrial Building Construction";
		break;
		case "236210":
		return "Industrial Building Construction";
		break;
		case "23622":
		return "Commercial and Institutional Building Construction";
		break;
		case "236220":
		return "Commercial and Institutional Building Construction";
		break;
		case "237":
		return "Heavy and Civil Engineering Construction";
		break;
		case "2371":
		return "Utility System Construction";
		break;
		case "23711":
		return "Water and Sewer Line and Related Structures Construction";
		break;
		case "237110":
		return "Water and Sewer Line and Related Structures Construction";
		break;
		case "23712":
		return "Oil and Gas Pipeline and Related Structures Construction";
		break;
		case "237120":
		return "Oil and Gas Pipeline and Related Structures Construction";
		break;
		case "23713":
		return "Power and Communication Line and Related Structures Construction";
		break;
		case "237130":
		return "Power and Communication Line and Related Structures Construction";
		break;
		case "2372":
		return "Land Subdivision";
		break;
		case "23721":
		return "Land Subdivision";
		break;
		case "237210":
		return "Land Subdivision";
		break;
		case "2373":
		return "Highway, Street, and Bridge Construction";
		break;
		case "23731":
		return "Highway, Street, and Bridge Construction";
		break;
		case "237310":
		return "Highway, Street, and Bridge Construction";
		break;
		case "2379":
		return "Other Heavy and Civil Engineering Construction";
		break;
		case "23799":
		return "Other Heavy and Civil Engineering Construction";
		break;
		case "237990":
		return "Other Heavy and Civil Engineering Construction";
		break;
		case "238":
		return "Specialty Trade Contractors";
		break;
		case "2381":
		return "Foundation, Structure, and Building Exterior Contractors";
		break;
		case "23811":
		return "Poured Concrete Foundation and Structure Contractors";
		break;
		case "238110":
		return "Poured Concrete Foundation and Structure Contractors";
		break;
		case "23812":
		return "Structural Steel and Precast Concrete Contractors";
		break;
		case "238120":
		return "Structural Steel and Precast Concrete Contractors";
		break;
		case "23813":
		return "Framing Contractors";
		break;
		case "238130":
		return "Framing Contractors";
		break;
		case "23814":
		return "Masonry Contractors";
		break;
		case "238140":
		return "Masonry Contractors";
		break;
		case "23815":
		return "Glass and Glazing Contractors";
		break;
		case "238150":
		return "Glass and Glazing Contractors";
		break;
		case "23816":
		return "Roofing Contractors";
		break;
		case "238160":
		return "Roofing Contractors";
		break;
		case "23817":
		return "Siding Contractors";
		break;
		case "238170":
		return "Siding Contractors";
		break;
		case "23819":
		return "Other Foundation, Structure, and Building Exterior Contractors";
		break;
		case "238190":
		return "Other Foundation, Structure, and Building Exterior Contractors";
		break;
		case "2382":
		return "Building Equipment Contractors";
		break;
		case "23821":
		return "Electrical Contractors and Other Wiring Installation Contractors";
		break;
		case "238210":
		return "Electrical Contractors and Other Wiring Installation Contractors";
		break;
		case "23822":
		return "Plumbing, Heating, and Air-Conditioning Contractors";
		break;
		case "238220":
		return "Plumbing, Heating, and Air-Conditioning Contractors";
		break;
		case "23829":
		return "Other Building Equipment Contractors";
		break;
		case "238290":
		return "Other Building Equipment Contractors";
		break;
		case "2383":
		return "Building Finishing Contractors";
		break;
		case "23831":
		return "Drywall and Insulation Contractors";
		break;
		case "238310":
		return "Drywall and Insulation Contractors";
		break;
		case "23832":
		return "Painting and Wall Covering Contractors";
		break;
		case "238320":
		return "Painting and Wall Covering Contractors";
		break;
		case "23833":
		return "Flooring Contractors";
		break;
		case "238330":
		return "Flooring Contractors";
		break;
		case "23834":
		return "Tile and Terrazzo Contractors";
		break;
		case "238340":
		return "Tile and Terrazzo Contractors";
		break;
		case "23835":
		return "Finish Carpentry Contractors";
		break;
		case "238350":
		return "Finish Carpentry Contractors";
		break;
		case "23839":
		return "Other Building Finishing Contractors";
		break;
		case "238390":
		return "Other Building Finishing Contractors";
		break;
		case "2389":
		return "Other Specialty Trade Contractors";
		break;
		case "23891":
		return "Site Preparation Contractors";
		break;
		case "238910":
		return "Site Preparation Contractors";
		break;
		case "23899":
		return "All Other Specialty Trade Contractors";
		break;
		case "238990":
		return "All Other Specialty Trade Contractors";
		break;
		case "31-33":
		return "Manufacturing";
		break;
		case "311":
		return "Food Manufacturing";
		break;
		case "3111":
		return "Animal Food Manufacturing";
		break;
		case "31111":
		return "Animal Food Manufacturing";
		break;
		case "311111":
		return "Dog and Cat Food Manufacturing";
		break;
		case "311119":
		return "Other Animal Food Manufacturing";
		break;
		case "3112":
		return "Grain and Oilseed Milling";
		break;
		case "31121":
		return "Flour Milling and Malt Manufacturing";
		break;
		case "311211":
		return "Flour Milling";
		break;
		case "311212":
		return "Rice Milling";
		break;
		case "311213":
		return "Malt Manufacturing";
		break;
		case "31122":
		return "Starch and Vegetable Fats and Oils Manufacturing";
		break;
		case "311221":
		return "Wet Corn Milling";
		break;
		case "311222":
		return "Soybean Processing";
		break;
		case "311223":
		return "Other Oilseed Processing";
		break;
		case "311225":
		return "Fats and Oils Refining and Blending";
		break;
		case "31123":
		return "Breakfast Cereal Manufacturing";
		break;
		case "311230":
		return "Breakfast Cereal Manufacturing";
		break;
		case "3113":
		return "Sugar and Confectionery Product Manufacturing";
		break;
		case "31131":
		return "Sugar Manufacturing";
		break;
		case "311311":
		return "Sugarcane Mills";
		break;
		case "311312":
		return "Cane Sugar Refining";
		break;
		case "311313":
		return "Beet Sugar Manufacturing";
		break;
		case "31132":
		return "Chocolate and Confectionery Manufacturing from Cacao Beans";
		break;
		case "311320":
		return "Chocolate and Confectionery Manufacturing from Cacao Beans";
		break;
		case "31133":
		return "Confectionery Manufacturing from Purchased Chocolate";
		break;
		case "311330":
		return "Confectionery Manufacturing from Purchased Chocolate";
		break;
		case "31134":
		return "Nonchocolate Confectionery Manufacturing";
		break;
		case "311340":
		return "Nonchocolate Confectionery Manufacturing";
		break;
		case "3114":
		return "Fruit and Vegetable Preserving and Specialty Food Manufacturing";
		break;
		case "31141":
		return "Frozen Food Manufacturing";
		break;
		case "311411":
		return "Frozen Fruit, Juice, and Vegetable Manufacturing";
		break;
		case "311412":
		return "Frozen Specialty Food Manufacturing";
		break;
		case "31142":
		return "Fruit and Vegetable Canning, Pickling, and Drying";
		break;
		case "311421":
		return "Fruit and Vegetable Canning";
		break;
		case "311422":
		return "Specialty Canning";
		break;
		case "311423":
		return "Dried and Dehydrated Food Manufacturing";
		break;
		case "3115":
		return "Dairy Product Manufacturing";
		break;
		case "31151":
		return "Dairy Product (except Frozen) Manufacturing";
		break;
		case "311511":
		return "Fluid Milk Manufacturing";
		break;
		case "311512":
		return "Creamery Butter Manufacturing";
		break;
		case "311513":
		return "Cheese Manufacturing";
		break;
		case "311514":
		return "Dry, Condensed, and Evaporated Dairy Product Manufacturing";
		break;
		case "31152":
		return "Ice Cream and Frozen Dessert Manufacturing";
		break;
		case "311520":
		return "Ice Cream and Frozen Dessert Manufacturing";
		break;
		case "3116":
		return "Animal Slaughtering and Processing";
		break;
		case "31161":
		return "Animal Slaughtering and Processing";
		break;
		case "311611":
		return "Animal (except Poultry) Slaughtering";
		break;
		case "311612":
		return "Meat Processed from Carcasses";
		break;
		case "311613":
		return "Rendering and Meat Byproduct Processing";
		break;
		case "311615":
		return "Poultry Processing";
		break;
		case "3117":
		return "Seafood Product Preparation and Packaging";
		break;
		case "31171":
		return "Seafood Product Preparation and Packaging";
		break;
		case "311711":
		return "Seafood Canning";
		break;
		case "311712":
		return "Fresh and Frozen Seafood Processing";
		break;
		case "3118":
		return "Bakeries and Tortilla Manufacturing";
		break;
		case "31181":
		return "Bread and Bakery Product Manufacturing";
		break;
		case "311811":
		return "Retail Bakeries";
		break;
		case "311812":
		return "Commercial Bakeries";
		break;
		case "311813":
		return "Frozen Cakes, Pies, and Other Pastries Manufacturing";
		break;
		case "31182":
		return "Cookie, Cracker, and Pasta Manufacturing";
		break;
		case "311821":
		return "Cookie and Cracker Manufacturing";
		break;
		case "311822":
		return "Flour Mixes and Dough Manufacturing from Purchased Flour";
		break;
		case "311823":
		return "Dry Pasta Manufacturing";
		break;
		case "31183":
		return "Tortilla Manufacturing";
		break;
		case "311830":
		return "Tortilla Manufacturing";
		break;
		case "3119":
		return "Other Food Manufacturing";
		break;
		case "31191":
		return "Snack Food Manufacturing";
		break;
		case "311911":
		return "Roasted Nuts and Peanut Butter Manufacturing";
		break;
		case "311919":
		return "Other Snack Food Manufacturing";
		break;
		case "31192":
		return "Coffee and Tea Manufacturing";
		break;
		case "311920":
		return "Coffee and Tea Manufacturing";
		break;
		case "31193":
		return "Flavoring Syrup and Concentrate Manufacturing";
		break;
		case "311930":
		return "Flavoring Syrup and Concentrate Manufacturing";
		break;
		case "31194":
		return "Seasoning and Dressing Manufacturing";
		break;
		case "311941":
		return "Mayonnaise, Dressing, and Other Prepared Sauce Manufacturing";
		break;
		case "311942":
		return "Spice and Extract Manufacturing";
		break;
		case "31199":
		return "All Other Food Manufacturing";
		break;
		case "311991":
		return "Perishable Prepared Food Manufacturing";
		break;
		case "311999":
		return "All Other Miscellaneous Food Manufacturing";
		break;
		case "312":
		return "Beverage and Tobacco Product Manufacturing";
		break;
		case "3121":
		return "Beverage Manufacturing";
		break;
		case "31211":
		return "Soft Drink and Ice Manufacturing";
		break;
		case "312111":
		return "Soft Drink Manufacturing";
		break;
		case "312112":
		return "Bottled Water Manufacturing";
		break;
		case "312113":
		return "Ice Manufacturing";
		break;
		case "31212":
		return "Breweries";
		break;
		case "312120":
		return "Breweries";
		break;
		case "31213":
		return "Wineries";
		break;
		case "312130":
		return "Wineries";
		break;
		case "31214":
		return "Distilleries";
		break;
		case "312140":
		return "Distilleries";
		break;
		case "3122":
		return "Tobacco Manufacturing";
		break;
		case "31221":
		return "Tobacco Stemming and Redrying";
		break;
		case "312210":
		return "Tobacco Stemming and Redrying";
		break;
		case "31222":
		return "Tobacco Product Manufacturing";
		break;
		case "312221":
		return "Cigarette Manufacturing";
		break;
		case "312229":
		return "Other Tobacco Product Manufacturing";
		break;
		case "313":
		return "Textile Mills";
		break;
		case "3131":
		return "Fiber, Yarn, and Thread Mills";
		break;
		case "31311":
		return "Fiber, Yarn, and Thread Mills";
		break;
		case "313111":
		return "Yarn Spinning Mills";
		break;
		case "313112":
		return "Yarn Texturizing, Throwing, and Twisting Mills";
		break;
		case "313113":
		return "Thread Mills";
		break;
		case "3132":
		return "Fabric Mills";
		break;
		case "31321":
		return "Broadwoven Fabric Mills";
		break;
		case "313210":
		return "Broadwoven Fabric Mills";
		break;
		case "31322":
		return "Narrow Fabric Mills and Schiffli Machine Embroidery";
		break;
		case "313221":
		return "Narrow Fabric Mills";
		break;
		case "313222":
		return "Schiffli Machine Embroidery";
		break;
		case "31323":
		return "Nonwoven Fabric Mills";
		break;
		case "313230":
		return "Nonwoven Fabric Mills";
		break;
		case "31324":
		return "Knit Fabric Mills";
		break;
		case "313241":
		return "Weft Knit Fabric Mills";
		break;
		case "313249":
		return "Other Knit Fabric and Lace Mills";
		break;
		case "3133":
		return "Textile and Fabric Finishing and Fabric Coating Mills";
		break;
		case "31331":
		return "Textile and Fabric Finishing Mills";
		break;
		case "313311":
		return "Broadwoven Fabric Finishing Mills";
		break;
		case "313312":
		return "Textile and Fabric Finishing (except Broadwoven Fabric) Mills";
		break;
		case "31332":
		return "Fabric Coating Mills";
		break;
		case "313320":
		return "Fabric Coating Mills";
		break;
		case "314":
		return "Textile Product Mills";
		break;
		case "3141":
		return "Textile Furnishings Mills";
		break;
		case "31411":
		return "Carpet and Rug Mills";
		break;
		case "314110":
		return "Carpet and Rug Mills";
		break;
		case "31412":
		return "Curtain and Linen Mills";
		break;
		case "314121":
		return "Curtain and Drapery Mills";
		break;
		case "314129":
		return "Other Household Textile Product Mills";
		break;
		case "3149":
		return "Other Textile Product Mills";
		break;
		case "31491":
		return "Textile Bag and Canvas Mills";
		break;
		case "314911":
		return "Textile Bag Mills";
		break;
		case "314912":
		return "Canvas and Related Product Mills";
		break;
		case "31499":
		return "All Other Textile Product Mills";
		break;
		case "314991":
		return "Rope, Cordage, and Twine Mills";
		break;
		case "314992":
		return "Tire Cord and Tire Fabric Mills";
		break;
		case "314999":
		return "All Other Miscellaneous Textile Product Mills";
		break;
		case "315":
		return "Apparel Manufacturing";
		break;
		case "3151":
		return "Apparel Knitting Mills";
		break;
		case "31511":
		return "Hosiery and Sock Mills";
		break;
		case "315111":
		return "Sheer Hosiery Mills";
		break;
		case "315119":
		return "Other Hosiery and Sock Mills";
		break;
		case "31519":
		return "Other Apparel Knitting Mills";
		break;
		case "315191":
		return "Outerwear Knitting Mills";
		break;
		case "315192":
		return "Underwear and Nightwear Knitting Mills";
		break;
		case "3152":
		return "Cut and Sew Apparel Manufacturing";
		break;
		case "31521":
		return "Cut and Sew Apparel Contractors";
		break;
		case "315211":
		return "Men's and Boys' Cut and Sew Apparel Contractors";
		break;
		case "315212":
		return "Women's, Girls', and Infants' Cut and Sew Apparel Contractors";
		break;
		case "31522":
		return "Men's and Boys' Cut and Sew Apparel Manufacturing";
		break;
		case "315221":
		return "Men's and Boys' Cut and Sew Underwear and Nightwear Manufacturing";
		break;
		case "315222":
		return "Men's and Boys' Cut and Sew Suit, Coat, and Overcoat Manufacturing";
		break;
		case "315223":
		return "Men's and Boys' Cut and Sew Shirt (except Work Shirt) Manufacturing";
		break;
		case "315224":
		return "Men's and Boys' Cut and Sew Trouser, Slack, and Jean Manufacturing";
		break;
		case "315225":
		return "Men's and Boys' Cut and Sew Work Clothing Manufacturing";
		break;
		case "315228":
		return "Men's and Boys' Cut and Sew Other Outerwear Manufacturing";
		break;
		case "31523":
		return "Women's and Girls' Cut and Sew Apparel Manufacturing";
		break;
		case "315231":
		return "Women's and Girls' Cut and Sew Lingerie, Loungewear, and Nightwear Manufacturing";
		break;
		case "315232":
		return "Women's and Girls' Cut and Sew Blouse and Shirt Manufacturing";
		break;
		case "315233":
		return "Women's and Girls' Cut and Sew Dress Manufacturing";
		break;
		case "315234":
		return "Women's and Girls' Cut and Sew Suit, Coat, Tailored Jacket, and Skirt Manufacturing";
		break;
		case "315239":
		return "Women's and Girls' Cut and Sew Other Outerwear Manufacturing";
		break;
		case "31529":
		return "Other Cut and Sew Apparel Manufacturing";
		break;
		case "315291":
		return "Infants' Cut and Sew Apparel Manufacturing";
		break;
		case "315292":
		return "Fur and Leather Apparel Manufacturing";
		break;
		case "315299":
		return "All Other Cut and Sew Apparel Manufacturing";
		break;
		case "3159":
		return "Apparel Accessories and Other Apparel Manufacturing";
		break;
		case "31599":
		return "Apparel Accessories and Other Apparel Manufacturing";
		break;
		case "315991":
		return "Hat, Cap, and Millinery Manufacturing";
		break;
		case "315992":
		return "Glove and Mitten Manufacturing";
		break;
		case "315993":
		return "Men's and Boys' Neckwear Manufacturing";
		break;
		case "315999":
		return "Other Apparel Accessories and Other Apparel Manufacturing";
		break;
		case "316":
		return "Leather and Allied Product Manufacturing";
		break;
		case "3161":
		return "Leather and Hide Tanning and Finishing";
		break;
		case "31611":
		return "Leather and Hide Tanning and Finishing";
		break;
		case "316110":
		return "Leather and Hide Tanning and Finishing";
		break;
		case "3162":
		return "Footwear Manufacturing";
		break;
		case "31621":
		return "Footwear Manufacturing";
		break;
		case "316211":
		return "Rubber and Plastics Footwear Manufacturing";
		break;
		case "316212":
		return "House Slipper Manufacturing";
		break;
		case "316213":
		return "Men's Footwear (except Athletic) Manufacturing";
		break;
		case "316214":
		return "Women's Footwear (except Athletic) Manufacturing";
		break;
		case "316219":
		return "Other Footwear Manufacturing";
		break;
		case "3169":
		return "Other Leather and Allied Product Manufacturing";
		break;
		case "31699":
		return "Other Leather and Allied Product Manufacturing";
		break;
		case "316991":
		return "Luggage Manufacturing";
		break;
		case "316992":
		return "Women's Handbag and Purse Manufacturing";
		break;
		case "316993":
		return "Personal Leather Good (except Women's Handbag and Purse) Manufacturing";
		break;
		case "316999":
		return "All Other Leather Good and Allied Product Manufacturing";
		break;
		case "321":
		return "Wood Product Manufacturing";
		break;
		case "3211":
		return "Sawmills and Wood Preservation";
		break;
		case "32111":
		return "Sawmills and Wood Preservation";
		break;
		case "321113":
		return "Sawmills";
		break;
		case "321114":
		return "Wood Preservation";
		break;
		case "3212":
		return "Veneer, Plywood, and Engineered Wood Product Manufacturing";
		break;
		case "32121":
		return "Veneer, Plywood, and Engineered Wood Product Manufacturing";
		break;
		case "321211":
		return "Hardwood Veneer and Plywood Manufacturing";
		break;
		case "321212":
		return "Softwood Veneer and Plywood Manufacturing";
		break;
		case "321213":
		return "Engineered Wood Member (except Truss) Manufacturing";
		break;
		case "321214":
		return "Truss Manufacturing";
		break;
		case "321219":
		return "Reconstituted Wood Product Manufacturing";
		break;
		case "3219":
		return "Other Wood Product Manufacturing";
		break;
		case "32191":
		return "Millwork";
		break;
		case "321911":
		return "Wood Window and Door Manufacturing";
		break;
		case "321912":
		return "Cut Stock, Resawing Lumber, and Planing";
		break;
		case "321918":
		return "Other Millwork (including Flooring)";
		break;
		case "32192":
		return "Wood Container and Pallet Manufacturing";
		break;
		case "321920":
		return "Wood Container and Pallet Manufacturing";
		break;
		case "32199":
		return "All Other Wood Product Manufacturing";
		break;
		case "321991":
		return "Manufactured Home (Mobile Home) Manufacturing";
		break;
		case "321992":
		return "Prefabricated Wood Building Manufacturing";
		break;
		case "321999":
		return "All Other Miscellaneous Wood Product Manufacturing";
		break;
		case "322":
		return "Paper Manufacturing";
		break;
		case "3221":
		return "Pulp, Paper, and Paperboard Mills";
		break;
		case "32211":
		return "Pulp Mills";
		break;
		case "322110":
		return "Pulp Mills";
		break;
		case "32212":
		return "Paper Mills";
		break;
		case "322121":
		return "Paper (except Newsprint) Mills";
		break;
		case "322122":
		return "Newsprint Mills";
		break;
		case "32213":
		return "Paperboard Mills";
		break;
		case "322130":
		return "Paperboard Mills";
		break;
		case "3222":
		return "Converted Paper Product Manufacturing";
		break;
		case "32221":
		return "Paperboard Container Manufacturing";
		break;
		case "322211":
		return "Corrugated and Solid Fiber Box Manufacturing";
		break;
		case "322212":
		return "Folding Paperboard Box Manufacturing";
		break;
		case "322213":
		return "Setup Paperboard Box Manufacturing";
		break;
		case "322214":
		return "Fiber Can, Tube, Drum, and Similar Products Manufacturing";
		break;
		case "322215":
		return "Nonfolding Sanitary Food Container Manufacturing";
		break;
		case "32222":
		return "Paper Bag and Coated and Treated Paper Manufacturing";
		break;
		case "322221":
		return "Coated and Laminated Packaging Paper Manufacturing";
		break;
		case "322222":
		return "Coated and Laminated Paper Manufacturing";
		break;
		case "322223":
		return "Coated Paper Bag and Pouch Manufacturing";
		break;
		case "322224":
		return "Uncoated Paper and Multiwall Bag Manufacturing";
		break;
		case "322225":
		return "Laminated Aluminum Foil Manufacturing for Flexible Packaging Uses";
		break;
		case "322226":
		return "Surface-Coated Paperboard Manufacturing";
		break;
		case "32223":
		return "Stationery Product Manufacturing";
		break;
		case "322231":
		return "Die-Cut Paper and Paperboard Office Supplies Manufacturing";
		break;
		case "322232":
		return "Envelope Manufacturing";
		break;
		case "322233":
		return "Stationery, Tablet, and Related Product Manufacturing";
		break;
		case "32229":
		return "Other Converted Paper Product Manufacturing";
		break;
		case "322291":
		return "Sanitary Paper Product Manufacturing";
		break;
		case "322299":
		return "All Other Converted Paper Product Manufacturing";
		break;
		case "323":
		return "Printing and Related Support Activities";
		break;
		case "3231":
		return "Printing and Related Support Activities";
		break;
		case "32311":
		return "Printing";
		break;
		case "323110":
		return "Commercial Lithographic Printing";
		break;
		case "323111":
		return "Commercial Gravure Printing";
		break;
		case "323112":
		return "Commercial Flexographic Printing";
		break;
		case "323113":
		return "Commercial Screen Printing";
		break;
		case "323114":
		return "Quick Printing";
		break;
		case "323115":
		return "Digital Printing";
		break;
		case "323116":
		return "Manifold Business Forms Printing";
		break;
		case "323117":
		return "Books Printing";
		break;
		case "323118":
		return "Blankbook, Looseleaf Binders, and Devices Manufacturing";
		break;
		case "323119":
		return "Other Commercial Printing";
		break;
		case "32312":
		return "Support Activities for Printing";
		break;
		case "323121":
		return "Tradebinding and Related Work";
		break;
		case "323122":
		return "Prepress Services";
		break;
		case "324":
		return "Petroleum and Coal Products Manufacturing";
		break;
		case "3241":
		return "Petroleum and Coal Products Manufacturing";
		break;
		case "32411":
		return "Petroleum Refineries";
		break;
		case "324110":
		return "Petroleum Refineries";
		break;
		case "32412":
		return "Asphalt Paving, Roofing, and Saturated Materials Manufacturing";
		break;
		case "324121":
		return "Asphalt Paving Mixture and Block Manufacturing";
		break;
		case "324122":
		return "Asphalt Shingle and Coating Materials Manufacturing";
		break;
		case "32419":
		return "Other Petroleum and Coal Products Manufacturing";
		break;
		case "324191":
		return "Petroleum Lubricating Oil and Grease Manufacturing";
		break;
		case "324199":
		return "All Other Petroleum and Coal Products Manufacturing";
		break;
		case "325":
		return "Chemical Manufacturing";
		break;
		case "3251":
		return "Basic Chemical Manufacturing";
		break;
		case "32511":
		return "Petrochemical Manufacturing";
		break;
		case "325110":
		return "Petrochemical Manufacturing";
		break;
		case "32512":
		return "Industrial Gas Manufacturing";
		break;
		case "325120":
		return "Industrial Gas Manufacturing";
		break;
		case "32513":
		return "Synthetic Dye and Pigment Manufacturing";
		break;
		case "325131":
		return "Inorganic Dye and Pigment Manufacturing";
		break;
		case "325132":
		return "Synthetic Organic Dye and Pigment Manufacturing";
		break;
		case "32518":
		return "Other Basic Inorganic Chemical Manufacturing";
		break;
		case "325181":
		return "Alkalies and Chlorine Manufacturing";
		break;
		case "325182":
		return "Carbon Black Manufacturing";
		break;
		case "325188":
		return "All Other Basic Inorganic Chemical Manufacturing";
		break;
		case "32519":
		return "Other Basic Organic Chemical Manufacturing";
		break;
		case "325191":
		return "Gum and Wood Chemical Manufacturing";
		break;
		case "325192":
		return "Cyclic Crude and Intermediate Manufacturing";
		break;
		case "325193":
		return "Ethyl Alcohol Manufacturing";
		break;
		case "325199":
		return "All Other Basic Organic Chemical Manufacturing";
		break;
		case "3252":
		return "Resin, Synthetic Rubber, and Artificial Synthetic Fibers and Filaments Manufacturing";
		break;
		case "32521":
		return "Resin and Synthetic Rubber Manufacturing";
		break;
		case "325211":
		return "Plastics Material and Resin Manufacturing";
		break;
		case "325212":
		return "Synthetic Rubber Manufacturing";
		break;
		case "32522":
		return "Artificial and Synthetic Fibers and Filaments Manufacturing";
		break;
		case "325221":
		return "Cellulosic Organic Fiber Manufacturing";
		break;
		case "325222":
		return "Noncellulosic Organic Fiber Manufacturing";
		break;
		case "3253":
		return "Pesticide, Fertilizer, and Other Agricultural Chemical Manufacturing";
		break;
		case "32531":
		return "Fertilizer Manufacturing";
		break;
		case "325311":
		return "Nitrogenous Fertilizer Manufacturing";
		break;
		case "325312":
		return "Phosphatic Fertilizer Manufacturing";
		break;
		case "325314":
		return "Fertilizer (Mixing Only) Manufacturing";
		break;
		case "32532":
		return "Pesticide and Other Agricultural Chemical Manufacturing";
		break;
		case "325320":
		return "Pesticide and Other Agricultural Chemical Manufacturing";
		break;
		case "3254":
		return "Pharmaceutical and Medicine Manufacturing";
		break;
		case "32541":
		return "Pharmaceutical and Medicine Manufacturing";
		break;
		case "325411":
		return "Medicinal and Botanical Manufacturing";
		break;
		case "325412":
		return "Pharmaceutical Preparation Manufacturing";
		break;
		case "325413":
		return "In-Vitro Diagnostic Substance Manufacturing";
		break;
		case "325414":
		return "Biological Product (except Diagnostic) Manufacturing";
		break;
		case "3255":
		return "Paint, Coating, and Adhesive Manufacturing";
		break;
		case "32551":
		return "Paint and Coating Manufacturing";
		break;
		case "325510":
		return "Paint and Coating Manufacturing";
		break;
		case "32552":
		return "Adhesive Manufacturing";
		break;
		case "325520":
		return "Adhesive Manufacturing";
		break;
		case "3256":
		return "Soap, Cleaning Compound, and Toilet Preparation Manufacturing";
		break;
		case "32561":
		return "Soap and Cleaning Compound Manufacturing";
		break;
		case "325611":
		return "Soap and Other Detergent Manufacturing";
		break;
		case "325612":
		return "Polish and Other Sanitation Good Manufacturing";
		break;
		case "325613":
		return "Surface Active Agent Manufacturing";
		break;
		case "32562":
		return "Toilet Preparation Manufacturing";
		break;
		case "325620":
		return "Toilet Preparation Manufacturing";
		break;
		case "3259":
		return "Other Chemical Product and Preparation Manufacturing";
		break;
		case "32591":
		return "Printing Ink Manufacturing";
		break;
		case "325910":
		return "Printing Ink Manufacturing";
		break;
		case "32592":
		return "Explosives Manufacturing";
		break;
		case "325920":
		return "Explosives Manufacturing";
		break;
		case "32599":
		return "All Other Chemical Product and Preparation Manufacturing";
		break;
		case "325991":
		return "Custom Compounding of Purchased Resins";
		break;
		case "325992":
		return "Photographic Film, Paper, Plate, and Chemical Manufacturing";
		break;
		case "325998":
		return "All Other Miscellaneous Chemical Product and Preparation Manufacturing";
		break;
		case "326":
		return "Plastics and Rubber Products Manufacturing";
		break;
		case "3261":
		return "Plastics Product Manufacturing";
		break;
		case "32611":
		return "Plastics Packaging Materials and Unlaminated Film and Sheet Manufacturing";
		break;
		case "326111":
		return "Plastics Bag and Pouch Manufacturing";
		break;
		case "326112":
		return "Plastics Packaging Film and Sheet (including Laminated) Manufacturing";
		break;
		case "326113":
		return "Unlaminated Plastics Film and Sheet (except Packaging) Manufacturing";
		break;
		case "32612":
		return "Plastics Pipe, Pipe Fitting, and Unlaminated Profile Shape Manufacturing";
		break;
		case "326121":
		return "Unlaminated Plastics Profile Shape Manufacturing";
		break;
		case "326122":
		return "Plastics Pipe and Pipe Fitting Manufacturing";
		break;
		case "32613":
		return "Laminated Plastics Plate, Sheet (except Packaging), and Shape Manufacturing";
		break;
		case "326130":
		return "Laminated Plastics Plate, Sheet (except Packaging), and Shape Manufacturing";
		break;
		case "32614":
		return "Polystyrene Foam Product Manufacturing";
		break;
		case "326140":
		return "Polystyrene Foam Product Manufacturing";
		break;
		case "32615":
		return "Urethane and Other Foam Product (except Polystyrene) Manufacturing";
		break;
		case "326150":
		return "Urethane and Other Foam Product (except Polystyrene) Manufacturing";
		break;
		case "32616":
		return "Plastics Bottle Manufacturing";
		break;
		case "326160":
		return "Plastics Bottle Manufacturing";
		break;
		case "32619":
		return "Other Plastics Product Manufacturing";
		break;
		case "326191":
		return "Plastics Plumbing Fixture Manufacturing";
		break;
		case "326192":
		return "Resilient Floor Covering Manufacturing";
		break;
		case "326199":
		return "All Other Plastics Product Manufacturing";
		break;
		case "3262":
		return "Rubber Product Manufacturing";
		break;
		case "32621":
		return "Tire Manufacturing";
		break;
		case "326211":
		return "Tire Manufacturing (except Retreading)";
		break;
		case "326212":
		return "Tire Retreading";
		break;
		case "32622":
		return "Rubber and Plastics Hoses and Belting Manufacturing";
		break;
		case "326220":
		return "Rubber and Plastics Hoses and Belting Manufacturing";
		break;
		case "32629":
		return "Other Rubber Product Manufacturing";
		break;
		case "326291":
		return "Rubber Product Manufacturing for Mechanical Use";
		break;
		case "326299":
		return "All Other Rubber Product Manufacturing";
		break;
		case "327":
		return "Nonmetallic Mineral Product Manufacturing";
		break;
		case "3271":
		return "Clay Product and Refractory Manufacturing";
		break;
		case "32711":
		return "Pottery, Ceramics, and Plumbing Fixture Manufacturing";
		break;
		case "327111":
		return "Vitreous China Plumbing Fixture and China and Earthenware Bathroom Accessories Manufacturing";
		break;
		case "327112":
		return "Vitreous China, Fine Earthenware, and Other Pottery Product Manufacturing";
		break;
		case "327113":
		return "Porcelain Electrical Supply Manufacturing";
		break;
		case "32712":
		return "Clay Building Material and Refractories Manufacturing";
		break;
		case "327121":
		return "Brick and Structural Clay Tile Manufacturing";
		break;
		case "327122":
		return "Ceramic Wall and Floor Tile Manufacturing";
		break;
		case "327123":
		return "Other Structural Clay Product Manufacturing";
		break;
		case "327124":
		return "Clay Refractory Manufacturing";
		break;
		case "327125":
		return "Nonclay Refractory Manufacturing";
		break;
		case "3272":
		return "Glass and Glass Product Manufacturing";
		break;
		case "32721":
		return "Glass and Glass Product Manufacturing";
		break;
		case "327211":
		return "Flat Glass Manufacturing";
		break;
		case "327212":
		return "Other Pressed and Blown Glass and Glassware Manufacturing";
		break;
		case "327213":
		return "Glass Container Manufacturing";
		break;
		case "327215":
		return "Glass Product Manufacturing Made of Purchased Glass";
		break;
		case "3273":
		return "Cement and Concrete Product Manufacturing";
		break;
		case "32731":
		return "Cement Manufacturing";
		break;
		case "327310":
		return "Cement Manufacturing";
		break;
		case "32732":
		return "Ready-Mix Concrete Manufacturing";
		break;
		case "327320":
		return "Ready-Mix Concrete Manufacturing";
		break;
		case "32733":
		return "Concrete Pipe, Brick, and Block Manufacturing";
		break;
		case "327331":
		return "Concrete Block and Brick Manufacturing";
		break;
		case "327332":
		return "Concrete Pipe Manufacturing";
		break;
		case "32739":
		return "Other Concrete Product Manufacturing";
		break;
		case "327390":
		return "Other Concrete Product Manufacturing";
		break;
		case "3274":
		return "Lime and Gypsum Product Manufacturing";
		break;
		case "32741":
		return "Lime Manufacturing";
		break;
		case "327410":
		return "Lime Manufacturing";
		break;
		case "32742":
		return "Gypsum Product Manufacturing";
		break;
		case "327420":
		return "Gypsum Product Manufacturing";
		break;
		case "3279":
		return "Other Nonmetallic Mineral Product Manufacturing";
		break;
		case "32791":
		return "Abrasive Product Manufacturing";
		break;
		case "327910":
		return "Abrasive Product Manufacturing";
		break;
		case "32799":
		return "All Other Nonmetallic Mineral Product Manufacturing";
		break;
		case "327991":
		return "Cut Stone and Stone Product Manufacturing";
		break;
		case "327992":
		return "Ground or Treated Mineral and Earth Manufacturing";
		break;
		case "327993":
		return "Mineral Wool Manufacturing";
		break;
		case "327999":
		return "All Other Miscellaneous Nonmetallic Mineral Product Manufacturing";
		break;
		case "331":
		return "Primary Metal Manufacturing";
		break;
		case "3311":
		return "Iron and Steel Mills and Ferroalloy Manufacturing";
		break;
		case "33111":
		return "Iron and Steel Mills and Ferroalloy Manufacturing";
		break;
		case "331111":
		return "Iron and Steel Mills";
		break;
		case "331112":
		return "Electrometallurgical Ferroalloy Product Manufacturing";
		break;
		case "3312":
		return "Steel Product Manufacturing from Purchased Steel";
		break;
		case "33121":
		return "Iron and Steel Pipe and Tube Manufacturing from Purchased Steel";
		break;
		case "331210":
		return "Iron and Steel Pipe and Tube Manufacturing from Purchased Steel";
		break;
		case "33122":
		return "Rolling and Drawing of Purchased Steel";
		break;
		case "331221":
		return "Rolled Steel Shape Manufacturing";
		break;
		case "331222":
		return "Steel Wire Drawing";
		break;
		case "3313":
		return "Alumina and Aluminum Production and Processing";
		break;
		case "33131":
		return "Alumina and Aluminum Production and Processing";
		break;
		case "331311":
		return "Alumina Refining";
		break;
		case "331312":
		return "Primary Aluminum Production";
		break;
		case "331314":
		return "Secondary Smelting and Alloying of Aluminum";
		break;
		case "331315":
		return "Aluminum Sheet, Plate, and Foil Manufacturing";
		break;
		case "331316":
		return "Aluminum Extruded Product Manufacturing";
		break;
		case "331319":
		return "Other Aluminum Rolling and Drawing";
		break;
		case "3314":
		return "Nonferrous Metal (except Aluminum) Production and Processing";
		break;
		case "33141":
		return "Nonferrous Metal (except Aluminum) Smelting and Refining";
		break;
		case "331411":
		return "Primary Smelting and Refining of Copper";
		break;
		case "331419":
		return "Primary Smelting and Refining of Nonferrous Metal (except Copper and Aluminum)";
		break;
		case "33142":
		return "Copper Rolling, Drawing, Extruding, and Alloying";
		break;
		case "331421":
		return "Copper Rolling, Drawing, and Extruding";
		break;
		case "331422":
		return "Copper Wire (except Mechanical) Drawing";
		break;
		case "331423":
		return "Secondary Smelting, Refining, and Alloying of Copper";
		break;
		case "33149":
		return "Nonferrous Metal (except Copper and Aluminum) Rolling, Drawing, Extruding, and Alloying";
		break;
		case "331491":
		return "Nonferrous Metal (except Copper and Aluminum) Rolling, Drawing, and Extruding";
		break;
		case "331492":
		return "Secondary Smelting, Refining, and Alloying of Nonferrous Metal (except Copper and Aluminum)";
		break;
		case "3315":
		return "Foundries";
		break;
		case "33151":
		return "Ferrous Metal Foundries";
		break;
		case "331511":
		return "Iron Foundries";
		break;
		case "331512":
		return "Steel Investment Foundries";
		break;
		case "331513":
		return "Steel Foundries (except Investment)";
		break;
		case "33152":
		return "Nonferrous Metal Foundries";
		break;
		case "331521":
		return "Aluminum Die-Casting Foundries";
		break;
		case "331522":
		return "Nonferrous (except Aluminum) Die-Casting Foundries";
		break;
		case "331524":
		return "Aluminum Foundries (except Die-Casting)";
		break;
		case "331525":
		return "Copper Foundries (except Die-Casting)";
		break;
		case "331528":
		return "Other Nonferrous Foundries (except Die-Casting)";
		break;
		case "332":
		return "Fabricated Metal Product Manufacturing";
		break;
		case "3321":
		return "Forging and Stamping";
		break;
		case "33211":
		return "Forging and Stamping";
		break;
		case "332111":
		return "Iron and Steel Forging";
		break;
		case "332112":
		return "Nonferrous Forging";
		break;
		case "332114":
		return "Custom Roll Forming";
		break;
		case "332115":
		return "Crown and Closure Manufacturing";
		break;
		case "332116":
		return "Metal Stamping";
		break;
		case "332117":
		return "Powder Metallurgy Part Manufacturing";
		break;
		case "3322":
		return "Cutlery and Handtool Manufacturing";
		break;
		case "33221":
		return "Cutlery and Handtool Manufacturing";
		break;
		case "332211":
		return "Cutlery and Flatware (except Precious) Manufacturing";
		break;
		case "332212":
		return "Hand and Edge Tool Manufacturing";
		break;
		case "332213":
		return "Saw Blade and Handsaw Manufacturing";
		break;
		case "332214":
		return "Kitchen Utensil, Pot, and Pan Manufacturing";
		break;
		case "3323":
		return "Architectural and Structural Metals Manufacturing";
		break;
		case "33231":
		return "Plate Work and Fabricated Structural Product Manufacturing";
		break;
		case "332311":
		return "Prefabricated Metal Building and Component Manufacturing";
		break;
		case "332312":
		return "Fabricated Structural Metal Manufacturing";
		break;
		case "332313":
		return "Plate Work Manufacturing";
		break;
		case "33232":
		return "Ornamental and Architectural Metal Products Manufacturing";
		break;
		case "332321":
		return "Metal Window and Door Manufacturing";
		break;
		case "332322":
		return "Sheet Metal Work Manufacturing";
		break;
		case "332323":
		return "Ornamental and Architectural Metal Work Manufacturing";
		break;
		case "3324":
		return "Boiler, Tank, and Shipping Container Manufacturing";
		break;
		case "33241":
		return "Power Boiler and Heat Exchanger Manufacturing";
		break;
		case "332410":
		return "Power Boiler and Heat Exchanger Manufacturing";
		break;
		case "33242":
		return "Metal Tank (Heavy Gauge) Manufacturing";
		break;
		case "332420":
		return "Metal Tank (Heavy Gauge) Manufacturing";
		break;
		case "33243":
		return "Metal Can, Box, and Other Metal Container (Light Gauge) Manufacturing";
		break;
		case "332431":
		return "Metal Can Manufacturing";
		break;
		case "332439":
		return "Other Metal Container Manufacturing";
		break;
		case "3325":
		return "Hardware Manufacturing";
		break;
		case "33251":
		return "Hardware Manufacturing";
		break;
		case "332510":
		return "Hardware Manufacturing";
		break;
		case "3326":
		return "Spring and Wire Product Manufacturing";
		break;
		case "33261":
		return "Spring and Wire Product Manufacturing";
		break;
		case "332611":
		return "Spring (Heavy Gauge) Manufacturing";
		break;
		case "332612":
		return "Spring (Light Gauge) Manufacturing";
		break;
		case "332618":
		return "Other Fabricated Wire Product Manufacturing";
		break;
		case "3327":
		return "Machine Shops; Turned Product; and Screw, Nut, and Bolt Manufacturing";
		break;
		case "33271":
		return "Machine Shops";
		break;
		case "332710":
		return "Machine Shops";
		break;
		case "33272":
		return "Turned Product and Screw, Nut, and Bolt Manufacturing";
		break;
		case "332721":
		return "Precision Turned Product Manufacturing";
		break;
		case "332722":
		return "Bolt, Nut, Screw, Rivet, and Washer Manufacturing";
		break;
		case "3328":
		return "Coating, Engraving, Heat Treating, and Allied Activities";
		break;
		case "33281":
		return "Coating, Engraving, Heat Treating, and Allied Activities";
		break;
		case "332811":
		return "Metal Heat Treating";
		break;
		case "332812":
		return "Metal Coating, Engraving (except Jewelry and Silverware), and Allied Services to Manufacturers";
		break;
		case "332813":
		return "Electroplating, Plating, Polishing, Anodizing, and Coloring";
		break;
		case "3329":
		return "Other Fabricated Metal Product Manufacturing";
		break;
		case "33291":
		return "Metal Valve Manufacturing";
		break;
		case "332911":
		return "Industrial Valve Manufacturing";
		break;
		case "332912":
		return "Fluid Power Valve and Hose Fitting Manufacturing";
		break;
		case "332913":
		return "Plumbing Fixture Fitting and Trim Manufacturing";
		break;
		case "332919":
		return "Other Metal Valve and Pipe Fitting Manufacturing";
		break;
		case "33299":
		return "All Other Fabricated Metal Product Manufacturing";
		break;
		case "332991":
		return "Ball and Roller Bearing Manufacturing";
		break;
		case "332992":
		return "Small Arms Ammunition Manufacturing";
		break;
		case "332993":
		return "Ammunition (except Small Arms) Manufacturing";
		break;
		case "332994":
		return "Small Arms Manufacturing";
		break;
		case "332995":
		return "Other Ordnance and Accessories Manufacturing";
		break;
		case "332996":
		return "Fabricated Pipe and Pipe Fitting Manufacturing";
		break;
		case "332997":
		return "Industrial Pattern Manufacturing";
		break;
		case "332998":
		return "Enameled Iron and Metal Sanitary Ware Manufacturing";
		break;
		case "332999":
		return "All Other Miscellaneous Fabricated Metal Product Manufacturing";
		break;
		case "333":
		return "Machinery Manufacturing";
		break;
		case "3331":
		return "Agriculture, Construction, and Mining Machinery Manufacturing";
		break;
		case "33311":
		return "Agricultural Implement Manufacturing";
		break;
		case "333111":
		return "Farm Machinery and Equipment Manufacturing";
		break;
		case "333112":
		return "Lawn and Garden Tractor and Home Lawn and Garden Equipment Manufacturing";
		break;
		case "33312":
		return "Construction Machinery Manufacturing";
		break;
		case "333120":
		return "Construction Machinery Manufacturing";
		break;
		case "33313":
		return "Mining and Oil and Gas Field Machinery Manufacturing";
		break;
		case "333131":
		return "Mining Machinery and Equipment Manufacturing";
		break;
		case "333132":
		return "Oil and Gas Field Machinery and Equipment Manufacturing";
		break;
		case "3332":
		return "Industrial Machinery Manufacturing";
		break;
		case "33321":
		return "Sawmill and Woodworking Machinery Manufacturing";
		break;
		case "333210":
		return "Sawmill and Woodworking Machinery Manufacturing";
		break;
		case "33322":
		return "Plastics and Rubber Industry Machinery Manufacturing";
		break;
		case "333220":
		return "Plastics and Rubber Industry Machinery Manufacturing";
		break;
		case "33329":
		return "Other Industrial Machinery Manufacturing";
		break;
		case "333291":
		return "Paper Industry Machinery Manufacturing";
		break;
		case "333292":
		return "Textile Machinery Manufacturing";
		break;
		case "333293":
		return "Printing Machinery and Equipment Manufacturing";
		break;
		case "333294":
		return "Food Product Machinery Manufacturing";
		break;
		case "333295":
		return "Semiconductor Machinery Manufacturing";
		break;
		case "333298":
		return "All Other Industrial Machinery Manufacturing";
		break;
		case "3333":
		return "Commercial and Service Industry Machinery Manufacturing";
		break;
		case "33331":
		return "Commercial and Service Industry Machinery Manufacturing";
		break;
		case "333311":
		return "Automatic Vending Machine Manufacturing";
		break;
		case "333312":
		return "Commercial Laundry, Drycleaning, and Pressing Machine Manufacturing";
		break;
		case "333313":
		return "Office Machinery Manufacturing";
		break;
		case "333314":
		return "Optical Instrument and Lens Manufacturing";
		break;
		case "333315":
		return "Photographic and Photocopying Equipment Manufacturing";
		break;
		case "333319":
		return "Other Commercial and Service Industry Machinery Manufacturing";
		break;
		case "3334":
		return "Ventilation, Heating, Air-Conditioning, and Commercial Refrigeration Equipment Manufacturing";
		break;
		case "33341":
		return "Ventilation, Heating, Air-Conditioning, and Commercial Refrigeration Equipment Manufacturing";
		break;
		case "333411":
		return "Air Purification Equipment Manufacturing";
		break;
		case "333412":
		return "Industrial and Commercial Fan and Blower Manufacturing";
		break;
		case "333414":
		return "Heating Equipment (except Warm Air Furnaces) Manufacturing";
		break;
		case "333415":
		return "Air-Conditioning and Warm Air Heating Equipment and Commercial and Industrial Refrigeration Equipment Manufacturing";
		break;
		case "3335":
		return "Metalworking Machinery Manufacturing";
		break;
		case "33351":
		return "Metalworking Machinery Manufacturing";
		break;
		case "333511":
		return "Industrial Mold Manufacturing";
		break;
		case "333512":
		return "Machine Tool (Metal Cutting Types) Manufacturing";
		break;
		case "333513":
		return "Machine Tool (Metal Forming Types) Manufacturing";
		break;
		case "333514":
		return "Special Die and Tool, Die Set, Jig, and Fixture Manufacturing";
		break;
		case "333515":
		return "Cutting Tool and Machine Tool Accessory Manufacturing";
		break;
		case "333516":
		return "Rolling Mill Machinery and Equipment Manufacturing";
		break;
		case "333518":
		return "Other Metalworking Machinery Manufacturing";
		break;
		case "3336":
		return "Engine, Turbine, and Power Transmission Equipment Manufacturing";
		break;
		case "33361":
		return "Engine, Turbine, and Power Transmission Equipment Manufacturing";
		break;
		case "333611":
		return "Turbine and Turbine Generator Set Units Manufacturing";
		break;
		case "333612":
		return "Speed Changer, Industrial High-Speed Drive, and Gear Manufacturing";
		break;
		case "333613":
		return "Mechanical Power Transmission Equipment Manufacturing";
		break;
		case "333618":
		return "Other Engine Equipment Manufacturing";
		break;
		case "3339":
		return "Other General Purpose Machinery Manufacturing";
		break;
		case "33391":
		return "Pump and Compressor Manufacturing";
		break;
		case "333911":
		return "Pump and Pumping Equipment Manufacturing";
		break;
		case "333912":
		return "Air and Gas Compressor Manufacturing";
		break;
		case "333913":
		return "Measuring and Dispensing Pump Manufacturing";
		break;
		case "33392":
		return "Material Handling Equipment Manufacturing";
		break;
		case "333921":
		return "Elevator and Moving Stairway Manufacturing";
		break;
		case "333922":
		return "Conveyor and Conveying Equipment Manufacturing";
		break;
		case "333923":
		return "Overhead Traveling Crane, Hoist, and Monorail System Manufacturing";
		break;
		case "333924":
		return "Industrial Truck, Tractor, Trailer, and Stacker Machinery Manufacturing";
		break;
		case "33399":
		return "All Other General Purpose Machinery Manufacturing";
		break;
		case "333991":
		return "Power-Driven Handtool Manufacturing";
		break;
		case "333992":
		return "Welding and Soldering Equipment Manufacturing";
		break;
		case "333993":
		return "Packaging Machinery Manufacturing";
		break;
		case "333994":
		return "Industrial Process Furnace and Oven Manufacturing";
		break;
		case "333995":
		return "Fluid Power Cylinder and Actuator Manufacturing";
		break;
		case "333996":
		return "Fluid Power Pump and Motor Manufacturing";
		break;
		case "333997":
		return "Scale and Balance Manufacturing";
		break;
		case "333999":
		return "All Other Miscellaneous General Purpose Machinery Manufacturing";
		break;
		case "334":
		return "Computer and Electronic Product Manufacturing";
		break;
		case "3341":
		return "Computer and Peripheral Equipment Manufacturing";
		break;
		case "33411":
		return "Computer and Peripheral Equipment Manufacturing";
		break;
		case "334111":
		return "Electronic Computer Manufacturing";
		break;
		case "334112":
		return "Computer Storage Device Manufacturing";
		break;
		case "334113":
		return "Computer Terminal Manufacturing";
		break;
		case "334119":
		return "Other Computer Peripheral Equipment Manufacturing";
		break;
		case "3342":
		return "Communications Equipment Manufacturing";
		break;
		case "33421":
		return "Telephone Apparatus Manufacturing";
		break;
		case "334210":
		return "Telephone Apparatus Manufacturing";
		break;
		case "33422":
		return "Radio and Television Broadcasting and Wireless Communications Equipment Manufacturing";
		break;
		case "334220":
		return "Radio and Television Broadcasting and Wireless Communications Equipment Manufacturing";
		break;
		case "33429":
		return "Other Communications Equipment Manufacturing";
		break;
		case "334290":
		return "Other Communications Equipment Manufacturing";
		break;
		case "3343":
		return "Audio and Video Equipment Manufacturing";
		break;
		case "33431":
		return "Audio and Video Equipment Manufacturing";
		break;
		case "334310":
		return "Audio and Video Equipment Manufacturing";
		break;
		case "3344":
		return "Semiconductor and Other Electronic Component Manufacturing";
		break;
		case "33441":
		return "Semiconductor and Other Electronic Component Manufacturing";
		break;
		case "334411":
		return "Electron Tube Manufacturing";
		break;
		case "334412":
		return "Bare Printed Circuit Board Manufacturing";
		break;
		case "334413":
		return "Semiconductor and Related Device Manufacturing";
		break;
		case "334414":
		return "Electronic Capacitor Manufacturing";
		break;
		case "334415":
		return "Electronic Resistor Manufacturing";
		break;
		case "334416":
		return "Electronic Coil, Transformer, and Other Inductor Manufacturing";
		break;
		case "334417":
		return "Electronic Connector Manufacturing";
		break;
		case "334418":
		return "Printed Circuit Assembly (Electronic Assembly) Manufacturing";
		break;
		case "334419":
		return "Other Electronic Component Manufacturing";
		break;
		case "3345":
		return "Navigational, Measuring, Electromedical, and Control Instruments Manufacturing";
		break;
		case "33451":
		return "Navigational, Measuring, Electromedical, and Control Instruments Manufacturing";
		break;
		case "334510":
		return "Electromedical and Electrotherapeutic Apparatus Manufacturing";
		break;
		case "334511":
		return "Search, Detection, Navigation, Guidance, Aeronautical, and Nautical System and Instrument Manufacturing";
		break;
		case "334512":
		return "Automatic Environmental Control Manufacturing for Residential, Commercial, and Appliance Use";
		break;
		case "334513":
		return "Instruments and Related Products Manufacturing for Measuring, Displaying, and Controlling Industrial Process Variables";
		break;
		case "334514":
		return "Totalizing Fluid Meter and Counting Device Manufacturing";
		break;
		case "334515":
		return "Instrument Manufacturing for Measuring and Testing Electricity and Electrical Signals";
		break;
		case "334516":
		return "Analytical Laboratory Instrument Manufacturing";
		break;
		case "334517":
		return "Irradiation Apparatus Manufacturing";
		break;
		case "334518":
		return "Watch, Clock, and Part Manufacturing";
		break;
		case "334519":
		return "Other Measuring and Controlling Device Manufacturing";
		break;
		case "3346":
		return "Manufacturing and Reproducing Magnetic and Optical Media";
		break;
		case "33461":
		return "Manufacturing and Reproducing Magnetic and Optical Media";
		break;
		case "334611":
		return "Software Reproducing";
		break;
		case "334612":
		return "Prerecorded Compact Disc (except Software), Tape, and Record Reproducing";
		break;
		case "334613":
		return "Magnetic and Optical Recording Media Manufacturing";
		break;
		case "335":
		return "Electrical Equipment, Appliance, and Component Manufacturing";
		break;
		case "3351":
		return "Electric Lighting Equipment Manufacturing";
		break;
		case "33511":
		return "Electric Lamp Bulb and Part Manufacturing";
		break;
		case "335110":
		return "Electric Lamp Bulb and Part Manufacturing";
		break;
		case "33512":
		return "Lighting Fixture Manufacturing";
		break;
		case "335121":
		return "Residential Electric Lighting Fixture Manufacturing";
		break;
		case "335122":
		return "Commercial, Industrial, and Institutional Electric Lighting Fixture Manufacturing";
		break;
		case "335129":
		return "Other Lighting Equipment Manufacturing";
		break;
		case "3352":
		return "Household Appliance Manufacturing";
		break;
		case "33521":
		return "Small Electrical Appliance Manufacturing";
		break;
		case "335211":
		return "Electric Housewares and Household Fan Manufacturing";
		break;
		case "335212":
		return "Household Vacuum Cleaner Manufacturing";
		break;
		case "33522":
		return "Major Appliance Manufacturing";
		break;
		case "335221":
		return "Household Cooking Appliance Manufacturing";
		break;
		case "335222":
		return "Household Refrigerator and Home Freezer Manufacturing";
		break;
		case "335224":
		return "Household Laundry Equipment Manufacturing";
		break;
		case "335228":
		return "Other Major Household Appliance Manufacturing";
		break;
		case "3353":
		return "Electrical Equipment Manufacturing";
		break;
		case "33531":
		return "Electrical Equipment Manufacturing";
		break;
		case "335311":
		return "Power, Distribution, and Specialty Transformer Manufacturing";
		break;
		case "335312":
		return "Motor and Generator Manufacturing";
		break;
		case "335313":
		return "Switchgear and Switchboard Apparatus Manufacturing";
		break;
		case "335314":
		return "Relay and Industrial Control Manufacturing";
		break;
		case "3359":
		return "Other Electrical Equipment and Component Manufacturing";
		break;
		case "33591":
		return "Battery Manufacturing";
		break;
		case "335911":
		return "Storage Battery Manufacturing";
		break;
		case "335912":
		return "Primary Battery Manufacturing";
		break;
		case "33592":
		return "Communication and Energy Wire and Cable Manufacturing";
		break;
		case "335921":
		return "Fiber Optic Cable Manufacturing";
		break;
		case "335929":
		return "Other Communication and Energy Wire Manufacturing";
		break;
		case "33593":
		return "Wiring Device Manufacturing";
		break;
		case "335931":
		return "Current-Carrying Wiring Device Manufacturing";
		break;
		case "335932":
		return "Noncurrent-Carrying Wiring Device Manufacturing";
		break;
		case "33599":
		return "All Other Electrical Equipment and Component Manufacturing";
		break;
		case "335991":
		return "Carbon and Graphite Product Manufacturing";
		break;
		case "335999":
		return "All Other Miscellaneous Electrical Equipment and Component Manufacturing";
		break;
		case "336":
		return "Transportation Equipment Manufacturing";
		break;
		case "3361":
		return "Motor Vehicle Manufacturing";
		break;
		case "33611":
		return "Automobile and Light Duty Motor Vehicle Manufacturing";
		break;
		case "336111":
		return "Automobile Manufacturing";
		break;
		case "336112":
		return "Light Truck and Utility Vehicle Manufacturing";
		break;
		case "33612":
		return "Heavy Duty Truck Manufacturing";
		break;
		case "336120":
		return "Heavy Duty Truck Manufacturing";
		break;
		case "3362":
		return "Motor Vehicle Body and Trailer Manufacturing";
		break;
		case "33621":
		return "Motor Vehicle Body and Trailer Manufacturing";
		break;
		case "336211":
		return "Motor Vehicle Body Manufacturing";
		break;
		case "336212":
		return "Truck Trailer Manufacturing";
		break;
		case "336213":
		return "Motor Home Manufacturing";
		break;
		case "336214":
		return "Travel Trailer and Camper Manufacturing";
		break;
		case "3363":
		return "Motor Vehicle Parts Manufacturing";
		break;
		case "33631":
		return "Motor Vehicle Gasoline Engine and Engine Parts Manufacturing";
		break;
		case "336311":
		return "Carburetor, Piston, Piston Ring, and Valve Manufacturing";
		break;
		case "336312":
		return "Gasoline Engine and Engine Parts Manufacturing";
		break;
		case "33632":
		return "Motor Vehicle Electrical and Electronic Equipment Manufacturing";
		break;
		case "336321":
		return "Vehicular Lighting Equipment Manufacturing";
		break;
		case "336322":
		return "Other Motor Vehicle Electrical and Electronic Equipment Manufacturing";
		break;
		case "33633":
		return "Motor Vehicle Steering and Suspension Components (except Spring) Manufacturing";
		break;
		case "336330":
		return "Motor Vehicle Steering and Suspension Components (except Spring) Manufacturing";
		break;
		case "33634":
		return "Motor Vehicle Brake System Manufacturing";
		break;
		case "336340":
		return "Motor Vehicle Brake System Manufacturing";
		break;
		case "33635":
		return "Motor Vehicle Transmission and Power Train Parts Manufacturing";
		break;
		case "336350":
		return "Motor Vehicle Transmission and Power Train Parts Manufacturing";
		break;
		case "33636":
		return "Motor Vehicle Seating and Interior Trim Manufacturing";
		break;
		case "336360":
		return "Motor Vehicle Seating and Interior Trim Manufacturing";
		break;
		case "33637":
		return "Motor Vehicle Metal Stamping";
		break;
		case "336370":
		return "Motor Vehicle Metal Stamping";
		break;
		case "33639":
		return "Other Motor Vehicle Parts Manufacturing";
		break;
		case "336391":
		return "Motor Vehicle Air-Conditioning Manufacturing";
		break;
		case "336399":
		return "All Other Motor Vehicle Parts Manufacturing";
		break;
		case "3364":
		return "Aerospace Product and Parts Manufacturing";
		break;
		case "33641":
		return "Aerospace Product and Parts Manufacturing";
		break;
		case "336411":
		return "Aircraft Manufacturing";
		break;
		case "336412":
		return "Aircraft Engine and Engine Parts Manufacturing";
		break;
		case "336413":
		return "Other Aircraft Parts and Auxiliary Equipment Manufacturing";
		break;
		case "336414":
		return "Guided Missile and Space Vehicle Manufacturing";
		break;
		case "336415":
		return "Guided Missile and Space Vehicle Propulsion Unit and Propulsion Unit Parts Manufacturing";
		break;
		case "336419":
		return "Other Guided Missile and Space Vehicle Parts and Auxiliary Equipment Manufacturing";
		break;
		case "3365":
		return "Railroad Rolling Stock Manufacturing";
		break;
		case "33651":
		return "Railroad Rolling Stock Manufacturing";
		break;
		case "336510":
		return "Railroad Rolling Stock Manufacturing";
		break;
		case "3366":
		return "Ship and Boat Building";
		break;
		case "33661":
		return "Ship and Boat Building";
		break;
		case "336611":
		return "Ship Building and Repairing";
		break;
		case "336612":
		return "Boat Building";
		break;
		case "3369":
		return "Other Transportation Equipment Manufacturing";
		break;
		case "33699":
		return "Other Transportation Equipment Manufacturing";
		break;
		case "336991":
		return "Motorcycle, Bicycle, and Parts Manufacturing";
		break;
		case "336992":
		return "Military Armored Vehicle, Tank, and Tank Component Manufacturing";
		break;
		case "336999":
		return "All Other Transportation Equipment Manufacturing";
		break;
		case "337":
		return "Furniture and Related Product Manufacturing";
		break;
		case "3371":
		return "Household and Institutional Furniture and Kitchen Cabinet Manufacturing";
		break;
		case "33711":
		return "Wood Kitchen Cabinet and Countertop Manufacturing";
		break;
		case "337110":
		return "Wood Kitchen Cabinet and Countertop Manufacturing";
		break;
		case "33712":
		return "Household and Institutional Furniture Manufacturing";
		break;
		case "337121":
		return "Upholstered Household Furniture Manufacturing";
		break;
		case "337122":
		return "Nonupholstered Wood Household Furniture Manufacturing";
		break;
		case "337124":
		return "Metal Household Furniture Manufacturing";
		break;
		case "337125":
		return "Household Furniture (except Wood and Metal) Manufacturing";
		break;
		case "337127":
		return "Institutional Furniture Manufacturing";
		break;
		case "337129":
		return "Wood Television, Radio, and Sewing Machine Cabinet Manufacturing";
		break;
		case "3372":
		return "Office Furniture (including Fixtures) Manufacturing";
		break;
		case "33721":
		return "Office Furniture (including Fixtures) Manufacturing";
		break;
		case "337211":
		return "Wood Office Furniture Manufacturing";
		break;
		case "337212":
		return "Custom Architectural Woodwork and Millwork Manufacturing";
		break;
		case "337214":
		return "Office Furniture (except Wood) Manufacturing";
		break;
		case "337215":
		return "Showcase, Partition, Shelving, and Locker Manufacturing";
		break;
		case "3379":
		return "Other Furniture Related Product Manufacturing";
		break;
		case "33791":
		return "Mattress Manufacturing";
		break;
		case "337910":
		return "Mattress Manufacturing";
		break;
		case "33792":
		return "Blind and Shade Manufacturing";
		break;
		case "337920":
		return "Blind and Shade Manufacturing";
		break;
		case "339":
		return "Miscellaneous Manufacturing";
		break;
		case "3391":
		return "Medical Equipment and Supplies Manufacturing";
		break;
		case "33911":
		return "Medical Equipment and Supplies Manufacturing";
		break;
		case "339112":
		return "Surgical and Medical Instrument Manufacturing";
		break;
		case "339113":
		return "Surgical Appliance and Supplies Manufacturing";
		break;
		case "339114":
		return "Dental Equipment and Supplies Manufacturing";
		break;
		case "339115":
		return "Ophthalmic Goods Manufacturing";
		break;
		case "339116":
		return "Dental Laboratories";
		break;
		case "3399":
		return "Other Miscellaneous Manufacturing";
		break;
		case "33991":
		return "Jewelry and Silverware Manufacturing";
		break;
		case "339911":
		return "Jewelry (except Costume) Manufacturing";
		break;
		case "339912":
		return "Silverware and Hollowware Manufacturing";
		break;
		case "339913":
		return "Jewelers' Material and Lapidary Work Manufacturing";
		break;
		case "339914":
		return "Costume Jewelry and Novelty Manufacturing";
		break;
		case "33992":
		return "Sporting and Athletic Goods Manufacturing";
		break;
		case "339920":
		return "Sporting and Athletic Goods Manufacturing";
		break;
		case "33993":
		return "Doll, Toy, and Game Manufacturing";
		break;
		case "339931":
		return "Doll and Stuffed Toy Manufacturing";
		break;
		case "339932":
		return "Game, Toy, and Children's Vehicle Manufacturing";
		break;
		case "33994":
		return "Office Supplies (except Paper) Manufacturing";
		break;
		case "339941":
		return "Pen and Mechanical Pencil Manufacturing";
		break;
		case "339942":
		return "Lead Pencil and Art Good Manufacturing";
		break;
		case "339943":
		return "Marking Device Manufacturing";
		break;
		case "339944":
		return "Carbon Paper and Inked Ribbon Manufacturing";
		break;
		case "33995":
		return "Sign Manufacturing";
		break;
		case "339950":
		return "Sign Manufacturing";
		break;
		case "33999":
		return "All Other Miscellaneous Manufacturing";
		break;
		case "339991":
		return "Gasket, Packing, and Sealing Device Manufacturing";
		break;
		case "339992":
		return "Musical Instrument Manufacturing";
		break;
		case "339993":
		return "Fastener, Button, Needle, and Pin Manufacturing";
		break;
		case "339994":
		return "Broom, Brush, and Mop Manufacturing";
		break;
		case "339995":
		return "Burial Casket Manufacturing";
		break;
		case "339999":
		return "All Other Miscellaneous Manufacturing";
		break;
		case "42":
		return "Wholesale Trade";
		break;
		case "423":
		return "Merchant Wholesalers, Durable Goods";
		break;
		case "4231":
		return "Motor Vehicle and Motor Vehicle Parts and Supplies Merchant Wholesalers";
		break;
		case "42311":
		return "Automobile and Other Motor Vehicle Merchant Wholesalers";
		break;
		case "423110":
		return "Automobile and Other Motor Vehicle Merchant Wholesalers";
		break;
		case "42312":
		return "Motor Vehicle Supplies and New Parts Merchant Wholesalers";
		break;
		case "423120":
		return "Motor Vehicle Supplies and New Parts Merchant Wholesalers";
		break;
		case "42313":
		return "Tire and Tube Merchant Wholesalers";
		break;
		case "423130":
		return "Tire and Tube Merchant Wholesalers";
		break;
		case "42314":
		return "Motor Vehicle Parts (Used) Merchant Wholesalers";
		break;
		case "423140":
		return "Motor Vehicle Parts (Used) Merchant Wholesalers";
		break;
		case "4232":
		return "Furniture and Home Furnishing Merchant Wholesalers";
		break;
		case "42321":
		return "Furniture Merchant Wholesalers";
		break;
		case "423210":
		return "Furniture Merchant Wholesalers";
		break;
		case "42322":
		return "Home Furnishing Merchant Wholesalers";
		break;
		case "423220":
		return "Home Furnishing Merchant Wholesalers";
		break;
		case "4233":
		return "Lumber and Other Construction Materials Merchant Wholesalers";
		break;
		case "42331":
		return "Lumber, Plywood, Millwork, and Wood Panel Merchant Wholesalers";
		break;
		case "423310":
		return "Lumber, Plywood, Millwork, and Wood Panel Merchant Wholesalers";
		break;
		case "42332":
		return "Brick, Stone, and Related Construction Material Merchant Wholesalers";
		break;
		case "423320":
		return "Brick, Stone, and Related Construction Material Merchant Wholesalers";
		break;
		case "42333":
		return "Roofing, Siding, and Insulation Material Merchant Wholesalers";
		break;
		case "423330":
		return "Roofing, Siding, and Insulation Material Merchant Wholesalers";
		break;
		case "42339":
		return "Other Construction Material Merchant Wholesalers";
		break;
		case "423390":
		return "Other Construction Material Merchant Wholesalers";
		break;
		case "4234":
		return "Professional and Commercial Equipment and Supplies Merchant Wholesalers";
		break;
		case "42341":
		return "Photographic Equipment and Supplies Merchant Wholesalers";
		break;
		case "423410":
		return "Photographic Equipment and Supplies Merchant Wholesalers";
		break;
		case "42342":
		return "Office Equipment Merchant Wholesalers";
		break;
		case "423420":
		return "Office Equipment Merchant Wholesalers";
		break;
		case "42343":
		return "Computer and Computer Peripheral Equipment and Software Merchant Wholesalers";
		break;
		case "423430":
		return "Computer and Computer Peripheral Equipment and Software Merchant Wholesalers";
		break;
		case "42344":
		return "Other Commercial Equipment Merchant Wholesalers";
		break;
		case "423440":
		return "Other Commercial Equipment Merchant Wholesalers";
		break;
		case "42345":
		return "Medical, Dental, and Hospital Equipment and Supplies Merchant Wholesalers";
		break;
		case "423450":
		return "Medical, Dental, and Hospital Equipment and Supplies Merchant Wholesalers";
		break;
		case "42346":
		return "Ophthalmic Goods Merchant Wholesalers";
		break;
		case "423460":
		return "Ophthalmic Goods Merchant Wholesalers";
		break;
		case "42349":
		return "Other Professional Equipment and Supplies Merchant Wholesalers";
		break;
		case "423490":
		return "Other Professional Equipment and Supplies Merchant Wholesalers";
		break;
		case "4235":
		return "Metal and Mineral (except Petroleum) Merchant Wholesalers";
		break;
		case "42351":
		return "Metal Service Centers and Other Metal Merchant Wholesalers";
		break;
		case "423510":
		return "Metal Service Centers and Other Metal Merchant Wholesalers";
		break;
		case "42352":
		return "Coal and Other Mineral and Ore Merchant Wholesalers";
		break;
		case "423520":
		return "Coal and Other Mineral and Ore Merchant Wholesalers";
		break;
		case "4236":
		return "Electrical and Electronic Goods Merchant Wholesalers";
		break;
		case "42361":
		return "Electrical Apparatus and Equipment, Wiring Supplies, and Related Equipment Merchant Wholesalers";
		break;
		case "423610":
		return "Electrical Apparatus and Equipment, Wiring Supplies, and Related Equipment Merchant Wholesalers";
		break;
		case "42362":
		return "Electrical and Electronic Appliance, Television, and Radio Set Merchant Wholesalers";
		break;
		case "423620":
		return "Electrical and Electronic Appliance, Television, and Radio Set Merchant Wholesalers";
		break;
		case "42369":
		return "Other Electronic Parts and Equipment Merchant Wholesalers";
		break;
		case "423690":
		return "Other Electronic Parts and Equipment Merchant Wholesalers";
		break;
		case "4237":
		return "Hardware, and Plumbing and Heating Equipment and Supplies Merchant Wholesalers";
		break;
		case "42371":
		return "Hardware Merchant Wholesalers";
		break;
		case "423710":
		return "Hardware Merchant Wholesalers";
		break;
		case "42372":
		return "Plumbing and Heating Equipment and Supplies (Hydronics) Merchant Wholesalers";
		break;
		case "423720":
		return "Plumbing and Heating Equipment and Supplies (Hydronics) Merchant Wholesalers";
		break;
		case "42373":
		return "Warm Air Heating and Air-Conditioning Equipment and Supplies Merchant Wholesalers";
		break;
		case "423730":
		return "Warm Air Heating and Air-Conditioning Equipment and Supplies Merchant Wholesalers";
		break;
		case "42374":
		return "Refrigeration Equipment and Supplies Merchant Wholesalers";
		break;
		case "423740":
		return "Refrigeration Equipment and Supplies Merchant Wholesalers";
		break;
		case "4238":
		return "Machinery, Equipment, and Supplies Merchant Wholesalers";
		break;
		case "42381":
		return "Construction and Mining (except Oil Well) Machinery and Equipment Merchant Wholesalers";
		break;
		case "423810":
		return "Construction and Mining (except Oil Well) Machinery and Equipment Merchant Wholesalers";
		break;
		case "42382":
		return "Farm and Garden Machinery and Equipment Merchant Wholesalers";
		break;
		case "423820":
		return "Farm and Garden Machinery and Equipment Merchant Wholesalers";
		break;
		case "42383":
		return "Industrial Machinery and Equipment Merchant Wholesalers";
		break;
		case "423830":
		return "Industrial Machinery and Equipment Merchant Wholesalers";
		break;
		case "42384":
		return "Industrial Supplies Merchant Wholesalers";
		break;
		case "423840":
		return "Industrial Supplies Merchant Wholesalers";
		break;
		case "42385":
		return "Service Establishment Equipment and Supplies Merchant Wholesalers";
		break;
		case "423850":
		return "Service Establishment Equipment and Supplies Merchant Wholesalers";
		break;
		case "42386":
		return "Transportation Equipment and Supplies (except Motor Vehicle) Merchant Wholesalers";
		break;
		case "423860":
		return "Transportation Equipment and Supplies (except Motor Vehicle) Merchant Wholesalers";
		break;
		case "4239":
		return "Miscellaneous Durable Goods Merchant Wholesalers";
		break;
		case "42391":
		return "Sporting and Recreational Goods and Supplies Merchant Wholesalers";
		break;
		case "423910":
		return "Sporting and Recreational Goods and Supplies Merchant Wholesalers";
		break;
		case "42392":
		return "Toy and Hobby Goods and Supplies Merchant Wholesalers";
		break;
		case "423920":
		return "Toy and Hobby Goods and Supplies Merchant Wholesalers";
		break;
		case "42393":
		return "Recyclable Material Merchant Wholesalers";
		break;
		case "423930":
		return "Recyclable Material Merchant Wholesalers";
		break;
		case "42394":
		return "Jewelry, Watch, Precious Stone, and Precious Metal Merchant Wholesalers";
		break;
		case "423940":
		return "Jewelry, Watch, Precious Stone, and Precious Metal Merchant Wholesalers";
		break;
		case "42399":
		return "Other Miscellaneous Durable Goods Merchant Wholesalers";
		break;
		case "423990":
		return "Other Miscellaneous Durable Goods Merchant Wholesalers";
		break;
		case "424":
		return "Merchant Wholesalers, Nondurable Goods";
		break;
		case "4241":
		return "Paper and Paper Product Merchant Wholesalers";
		break;
		case "42411":
		return "Printing and Writing Paper Merchant Wholesalers";
		break;
		case "424110":
		return "Printing and Writing Paper Merchant Wholesalers";
		break;
		case "42412":
		return "Stationery and Office Supplies Merchant Wholesalers";
		break;
		case "424120":
		return "Stationery and Office Supplies Merchant Wholesalers";
		break;
		case "42413":
		return "Industrial and Personal Service Paper Merchant Wholesalers";
		break;
		case "424130":
		return "Industrial and Personal Service Paper Merchant Wholesalers";
		break;
		case "4242":
		return "Drugs and Druggists' Sundries Merchant Wholesalers";
		break;
		case "42421":
		return "Drugs and Druggists' Sundries Merchant Wholesalers";
		break;
		case "424210":
		return "Drugs and Druggists' Sundries Merchant Wholesalers";
		break;
		case "4243":
		return "Apparel, Piece Goods, and Notions Merchant Wholesalers";
		break;
		case "42431":
		return "Piece Goods, Notions, and Other Dry Goods Merchant Wholesalers";
		break;
		case "424310":
		return "Piece Goods, Notions, and Other Dry Goods Merchant Wholesalers";
		break;
		case "42432":
		return "Men's and Boys' Clothing and Furnishings Merchant Wholesalers";
		break;
		case "424320":
		return "Men's and Boys' Clothing and Furnishings Merchant Wholesalers";
		break;
		case "42433":
		return "Women's, Children's, and Infants' Clothing and Accessories Merchant Wholesalers";
		break;
		case "424330":
		return "Women's, Children's, and Infants' Clothing and Accessories Merchant Wholesalers";
		break;
		case "42434":
		return "Footwear Merchant Wholesalers";
		break;
		case "424340":
		return "Footwear Merchant Wholesalers";
		break;
		case "4244":
		return "Grocery and Related Product Merchant Wholesalers";
		break;
		case "42441":
		return "General Line Grocery Merchant Wholesalers";
		break;
		case "424410":
		return "General Line Grocery Merchant Wholesalers";
		break;
		case "42442":
		return "Packaged Frozen Food Merchant Wholesalers";
		break;
		case "424420":
		return "Packaged Frozen Food Merchant Wholesalers";
		break;
		case "42443":
		return "Dairy Product (except Dried or Canned) Merchant Wholesalers";
		break;
		case "424430":
		return "Dairy Product (except Dried or Canned) Merchant Wholesalers";
		break;
		case "42444":
		return "Poultry and Poultry Product Merchant Wholesalers";
		break;
		case "424440":
		return "Poultry and Poultry Product Merchant Wholesalers";
		break;
		case "42445":
		return "Confectionery Merchant Wholesalers";
		break;
		case "424450":
		return "Confectionery Merchant Wholesalers";
		break;
		case "42446":
		return "Fish and Seafood Merchant Wholesalers";
		break;
		case "424460":
		return "Fish and Seafood Merchant Wholesalers";
		break;
		case "42447":
		return "Meat and Meat Product Merchant Wholesalers";
		break;
		case "424470":
		return "Meat and Meat Product Merchant Wholesalers";
		break;
		case "42448":
		return "Fresh Fruit and Vegetable Merchant Wholesalers";
		break;
		case "424480":
		return "Fresh Fruit and Vegetable Merchant Wholesalers";
		break;
		case "42449":
		return "Other Grocery and Related Products Merchant Wholesalers";
		break;
		case "424490":
		return "Other Grocery and Related Products Merchant Wholesalers";
		break;
		case "4245":
		return "Farm Product Raw Material Merchant Wholesalers";
		break;
		case "42451":
		return "Grain and Field Bean Merchant Wholesalers";
		break;
		case "424510":
		return "Grain and Field Bean Merchant Wholesalers";
		break;
		case "42452":
		return "Livestock Merchant Wholesalers";
		break;
		case "424520":
		return "Livestock Merchant Wholesalers";
		break;
		case "42459":
		return "Other Farm Product Raw Material Merchant Wholesalers";
		break;
		case "424590":
		return "Other Farm Product Raw Material Merchant Wholesalers";
		break;
		case "4246":
		return "Chemical and Allied Products Merchant Wholesalers";
		break;
		case "42461":
		return "Plastics Materials and Basic Forms and Shapes Merchant Wholesalers";
		break;
		case "424610":
		return "Plastics Materials and Basic Forms and Shapes Merchant Wholesalers";
		break;
		case "42469":
		return "Other Chemical and Allied Products Merchant Wholesalers";
		break;
		case "424690":
		return "Other Chemical and Allied Products Merchant Wholesalers";
		break;
		case "4247":
		return "Petroleum and Petroleum Products Merchant Wholesalers";
		break;
		case "42471":
		return "Petroleum Bulk Stations and Terminals";
		break;
		case "424710":
		return "Petroleum Bulk Stations and Terminals";
		break;
		case "42472":
		return "Petroleum and Petroleum Products Merchant Wholesalers (except Bulk Stations and Terminals)";
		break;
		case "424720":
		return "Petroleum and Petroleum Products Merchant Wholesalers (except Bulk Stations and Terminals)";
		break;
		case "4248":
		return "Beer, Wine, and Distilled Alcoholic Beverage Merchant Wholesalers";
		break;
		case "42481":
		return "Beer and Ale Merchant Wholesalers";
		break;
		case "424810":
		return "Beer and Ale Merchant Wholesalers";
		break;
		case "42482":
		return "Wine and Distilled Alcoholic Beverage Merchant Wholesalers";
		break;
		case "424820":
		return "Wine and Distilled Alcoholic Beverage Merchant Wholesalers";
		break;
		case "4249":
		return "Miscellaneous Nondurable Goods Merchant Wholesalers";
		break;
		case "42491":
		return "Farm Supplies Merchant Wholesalers";
		break;
		case "424910":
		return "Farm Supplies Merchant Wholesalers";
		break;
		case "42492":
		return "Book, Periodical, and Newspaper Merchant Wholesalers";
		break;
		case "424920":
		return "Book, Periodical, and Newspaper Merchant Wholesalers";
		break;
		case "42493":
		return "Flower, Nursery Stock, and Florists' Supplies Merchant Wholesalers";
		break;
		case "424930":
		return "Flower, Nursery Stock, and Florists' Supplies Merchant Wholesalers";
		break;
		case "42494":
		return "Tobacco and Tobacco Product Merchant Wholesalers";
		break;
		case "424940":
		return "Tobacco and Tobacco Product Merchant Wholesalers";
		break;
		case "42495":
		return "Paint, Varnish, and Supplies Merchant Wholesalers";
		break;
		case "424950":
		return "Paint, Varnish, and Supplies Merchant Wholesalers";
		break;
		case "42499":
		return "Other Miscellaneous Nondurable Goods Merchant Wholesalers";
		break;
		case "424990":
		return "Other Miscellaneous Nondurable Goods Merchant Wholesalers";
		break;
		case "425":
		return "Wholesale Electronic Markets and Agents and Brokers";
		break;
		case "4251":
		return "Wholesale Electronic Markets and Agents and Brokers";
		break;
		case "42511":
		return "Business to Business Electronic Markets";
		break;
		case "425110":
		return "Business to Business Electronic Markets";
		break;
		case "42512":
		return "Wholesale Trade Agents and Brokers";
		break;
		case "425120":
		return "Wholesale Trade Agents and Brokers";
		break;
		case "44-45":
		return "Retail Trade";
		break;
		case "441":
		return "Motor Vehicle and Parts Dealers";
		break;
		case "4411":
		return "Automobile Dealers";
		break;
		case "44111":
		return "New Car Dealers";
		break;
		case "441110":
		return "New Car Dealers";
		break;
		case "44112":
		return "Used Car Dealers";
		break;
		case "441120":
		return "Used Car Dealers";
		break;
		case "4412":
		return "Other Motor Vehicle Dealers";
		break;
		case "44121":
		return "Recreational Vehicle Dealers";
		break;
		case "441210":
		return "Recreational Vehicle Dealers";
		break;
		case "44122":
		return "Motorcycle, Boat, and Other Motor Vehicle Dealers";
		break;
		case "441221":
		return "Motorcycle, ATV, and Personal Watercraft Dealers";
		break;
		case "441222":
		return "Boat Dealers";
		break;
		case "441229":
		return "All Other Motor Vehicle Dealers";
		break;
		case "4413":
		return "Automotive Parts, Accessories, and Tire Stores";
		break;
		case "44131":
		return "Automotive Parts and Accessories Stores";
		break;
		case "441310":
		return "Automotive Parts and Accessories Stores";
		break;
		case "44132":
		return "Tire Dealers";
		break;
		case "441320":
		return "Tire Dealers";
		break;
		case "442":
		return "Furniture and Home Furnishings Stores";
		break;
		case "4421":
		return "Furniture Stores";
		break;
		case "44211":
		return "Furniture Stores";
		break;
		case "442110":
		return "Furniture Stores";
		break;
		case "4422":
		return "Home Furnishings Stores";
		break;
		case "44221":
		return "Floor Covering Stores";
		break;
		case "442210":
		return "Floor Covering Stores";
		break;
		case "44229":
		return "Other Home Furnishings Stores";
		break;
		case "442291":
		return "Window Treatment Stores";
		break;
		case "442299":
		return "All Other Home Furnishings Stores";
		break;
		case "443":
		return "Electronics and Appliance Stores";
		break;
		case "4431":
		return "Electronics and Appliance Stores";
		break;
		case "44311":
		return "Appliance, Television, and Other Electronics Stores";
		break;
		case "443111":
		return "Household Appliance Stores";
		break;
		case "443112":
		return "Radio, Television, and Other Electronics Stores";
		break;
		case "44312":
		return "Computer and Software Stores";
		break;
		case "443120":
		return "Computer and Software Stores";
		break;
		case "44313":
		return "Camera and Photographic Supplies Stores";
		break;
		case "443130":
		return "Camera and Photographic Supplies Stores";
		break;
		case "444":
		return "Building Material and Garden Equipment and Supplies Dealers";
		break;
		case "4441":
		return "Building Material and Supplies Dealers";
		break;
		case "44411":
		return "Home Centers";
		break;
		case "444110":
		return "Home Centers";
		break;
		case "44412":
		return "Paint and Wallpaper Stores";
		break;
		case "444120":
		return "Paint and Wallpaper Stores";
		break;
		case "44413":
		return "Hardware Stores";
		break;
		case "444130":
		return "Hardware Stores";
		break;
		case "44419":
		return "Other Building Material Dealers";
		break;
		case "444190":
		return "Other Building Material Dealers";
		break;
		case "4442":
		return "Lawn and Garden Equipment and Supplies Stores";
		break;
		case "44421":
		return "Outdoor Power Equipment Stores";
		break;
		case "444210":
		return "Outdoor Power Equipment Stores";
		break;
		case "44422":
		return "Nursery, Garden Center, and Farm Supply Stores";
		break;
		case "444220":
		return "Nursery, Garden Center, and Farm Supply Stores";
		break;
		case "445":
		return "Food and Beverage Stores";
		break;
		case "4451":
		return "Grocery Stores";
		break;
		case "44511":
		return "Supermarkets and Other Grocery (except Convenience) Stores";
		break;
		case "445110":
		return "Supermarkets and Other Grocery (except Convenience) Stores";
		break;
		case "44512":
		return "Convenience Stores";
		break;
		case "445120":
		return "Convenience Stores";
		break;
		case "4452":
		return "Specialty Food Stores";
		break;
		case "44521":
		return "Meat Markets";
		break;
		case "445210":
		return "Meat Markets";
		break;
		case "44522":
		return "Fish and Seafood Markets";
		break;
		case "445220":
		return "Fish and Seafood Markets";
		break;
		case "44523":
		return "Fruit and Vegetable Markets";
		break;
		case "445230":
		return "Fruit and Vegetable Markets";
		break;
		case "44529":
		return "Other Specialty Food Stores";
		break;
		case "445291":
		return "Baked Goods Stores";
		break;
		case "445292":
		return "Confectionery and Nut Stores";
		break;
		case "445299":
		return "All Other Specialty Food Stores";
		break;
		case "4453":
		return "Beer, Wine, and Liquor Stores";
		break;
		case "44531":
		return "Beer, Wine, and Liquor Stores";
		break;
		case "445310":
		return "Beer, Wine, and Liquor Stores";
		break;
		case "446":
		return "Health and Personal Care Stores";
		break;
		case "4461":
		return "Health and Personal Care Stores";
		break;
		case "44611":
		return "Pharmacies and Drug Stores";
		break;
		case "446110":
		return "Pharmacies and Drug Stores";
		break;
		case "44612":
		return "Cosmetics, Beauty Supplies, and Perfume Stores";
		break;
		case "446120":
		return "Cosmetics, Beauty Supplies, and Perfume Stores";
		break;
		case "44613":
		return "Optical Goods Stores";
		break;
		case "446130":
		return "Optical Goods Stores";
		break;
		case "44619":
		return "Other Health and Personal Care Stores";
		break;
		case "446191":
		return "Food (Health) Supplement Stores";
		break;
		case "446199":
		return "All Other Health and Personal Care Stores";
		break;
		case "447":
		return "Gasoline Stations";
		break;
		case "4471":
		return "Gasoline Stations";
		break;
		case "44711":
		return "Gasoline Stations with Convenience Stores";
		break;
		case "447110":
		return "Gasoline Stations with Convenience Stores";
		break;
		case "44719":
		return "Other Gasoline Stations";
		break;
		case "447190":
		return "Other Gasoline Stations";
		break;
		case "448":
		return "Clothing and Clothing Accessories Stores";
		break;
		case "4481":
		return "Clothing Stores";
		break;
		case "44811":
		return "Men's Clothing Stores";
		break;
		case "448110":
		return "Men's Clothing Stores";
		break;
		case "44812":
		return "Women's Clothing Stores";
		break;
		case "448120":
		return "Women's Clothing Stores";
		break;
		case "44813":
		return "Children's and Infants' Clothing Stores";
		break;
		case "448130":
		return "Children's and Infants' Clothing Stores";
		break;
		case "44814":
		return "Family Clothing Stores";
		break;
		case "448140":
		return "Family Clothing Stores";
		break;
		case "44815":
		return "Clothing Accessories Stores";
		break;
		case "448150":
		return "Clothing Accessories Stores";
		break;
		case "44819":
		return "Other Clothing Stores";
		break;
		case "448190":
		return "Other Clothing Stores";
		break;
		case "4482":
		return "Shoe Stores";
		break;
		case "44821":
		return "Shoe Stores";
		break;
		case "448210":
		return "Shoe Stores";
		break;
		case "4483":
		return "Jewelry, Luggage, and Leather Goods Stores";
		break;
		case "44831":
		return "Jewelry Stores";
		break;
		case "448310":
		return "Jewelry Stores";
		break;
		case "44832":
		return "Luggage and Leather Goods Stores";
		break;
		case "448320":
		return "Luggage and Leather Goods Stores";
		break;
		case "451":
		return "Sporting Goods, Hobby, Book, and Music Stores";
		break;
		case "4511":
		return "Sporting Goods, Hobby, and Musical Instrument Stores";
		break;
		case "45111":
		return "Sporting Goods Stores";
		break;
		case "451110":
		return "Sporting Goods Stores";
		break;
		case "45112":
		return "Hobby, Toy, and Game Stores";
		break;
		case "451120":
		return "Hobby, Toy, and Game Stores";
		break;
		case "45113":
		return "Sewing, Needlework, and Piece Goods Stores";
		break;
		case "451130":
		return "Sewing, Needlework, and Piece Goods Stores";
		break;
		case "45114":
		return "Musical Instrument and Supplies Stores";
		break;
		case "451140":
		return "Musical Instrument and Supplies Stores";
		break;
		case "4512":
		return "Book, Periodical, and Music Stores";
		break;
		case "45121":
		return "Book Stores and News Dealers";
		break;
		case "451211":
		return "Book Stores";
		break;
		case "451212":
		return "News Dealers and Newsstands";
		break;
		case "45122":
		return "Prerecorded Tape, Compact Disc, and Record Stores";
		break;
		case "451220":
		return "Prerecorded Tape, Compact Disc, and Record Stores";
		break;
		case "452":
		return "General Merchandise Stores";
		break;
		case "4521":
		return "Department Stores";
		break;
		case "45211":
		return "Department Stores";
		break;
		case "452111":
		return "Department Stores (except Discount Department Stores)";
		break;
		case "452112":
		return "Discount Department Stores";
		break;
		case "4529":
		return "Other General Merchandise Stores";
		break;
		case "45291":
		return "Warehouse Clubs and Supercenters";
		break;
		case "452910":
		return "Warehouse Clubs and Supercenters";
		break;
		case "45299":
		return "All Other General Merchandise Stores";
		break;
		case "452990":
		return "All Other General Merchandise Stores";
		break;
		case "453":
		return "Miscellaneous Store Retailers";
		break;
		case "4531":
		return "Florists";
		break;
		case "45311":
		return "Florists";
		break;
		case "453110":
		return "Florists";
		break;
		case "4532":
		return "Office Supplies, Stationery, and Gift Stores";
		break;
		case "45321":
		return "Office Supplies and Stationery Stores";
		break;
		case "453210":
		return "Office Supplies and Stationery Stores";
		break;
		case "45322":
		return "Gift, Novelty, and Souvenir Stores";
		break;
		case "453220":
		return "Gift, Novelty, and Souvenir Stores";
		break;
		case "4533":
		return "Used Merchandise Stores";
		break;
		case "45331":
		return "Used Merchandise Stores";
		break;
		case "453310":
		return "Used Merchandise Stores";
		break;
		case "4539":
		return "Other Miscellaneous Store Retailers";
		break;
		case "45391":
		return "Pet and Pet Supplies Stores";
		break;
		case "453910":
		return "Pet and Pet Supplies Stores";
		break;
		case "45392":
		return "Art Dealers";
		break;
		case "453920":
		return "Art Dealers";
		break;
		case "45393":
		return "Manufactured (Mobile) Home Dealers";
		break;
		case "453930":
		return "Manufactured (Mobile) Home Dealers";
		break;
		case "45399":
		return "All Other Miscellaneous Store Retailers";
		break;
		case "453991":
		return "Tobacco Stores";
		break;
		case "453998":
		return "All Other Miscellaneous Store Retailers (except Tobacco Stores)";
		break;
		case "454":
		return "Nonstore Retailers";
		break;
		case "4541":
		return "Electronic Shopping and Mail-Order Houses";
		break;
		case "45411":
		return "Electronic Shopping and Mail-Order Houses";
		break;
		case "454111":
		return "Electronic Shopping";
		break;
		case "454112":
		return "Electronic Auctions";
		break;
		case "454113":
		return "Mail-Order Houses";
		break;
		case "4542":
		return "Vending Machine Operators";
		break;
		case "45421":
		return "Vending Machine Operators";
		break;
		case "454210":
		return "Vending Machine Operators";
		break;
		case "4543":
		return "Direct Selling Establishments";
		break;
		case "45431":
		return "Fuel Dealers";
		break;
		case "454311":
		return "Heating Oil Dealers";
		break;
		case "454312":
		return "Liquefied Petroleum Gas (Bottled Gas) Dealers";
		break;
		case "454319":
		return "Other Fuel Dealers";
		break;
		case "45439":
		return "Other Direct Selling Establishments";
		break;
		case "454390":
		return "Other Direct Selling Establishments";
		break;
		case "48-49":
		return "Transportation and Warehousing";
		break;
		case "481":
		return "Air Transportation";
		break;
		case "4811":
		return "Scheduled Air Transportation";
		break;
		case "48111":
		return "Scheduled Air Transportation";
		break;
		case "481111":
		return "Scheduled Passenger Air Transportation";
		break;
		case "481112":
		return "Scheduled Freight Air Transportation";
		break;
		case "4812":
		return "Nonscheduled Air Transportation";
		break;
		case "48121":
		return "Nonscheduled Air Transportation";
		break;
		case "481211":
		return "Nonscheduled Chartered Passenger Air Transportation";
		break;
		case "481212":
		return "Nonscheduled Chartered Freight Air Transportation";
		break;
		case "481219":
		return "Other Nonscheduled Air Transportation";
		break;
		case "482":
		return "Rail Transportation";
		break;
		case "4821":
		return "Rail Transportation";
		break;
		case "48211":
		return "Rail Transportation";
		break;
		case "482111":
		return "Line-Haul Railroads";
		break;
		case "482112":
		return "Short Line Railroads";
		break;
		case "483":
		return "Water Transportation";
		break;
		case "4831":
		return "Deep Sea, Coastal, and Great Lakes Water Transportation";
		break;
		case "48311":
		return "Deep Sea, Coastal, and Great Lakes Water Transportation";
		break;
		case "483111":
		return "Deep Sea Freight Transportation";
		break;
		case "483112":
		return "Deep Sea Passenger Transportation";
		break;
		case "483113":
		return "Coastal and Great Lakes Freight Transportation";
		break;
		case "483114":
		return "Coastal and Great Lakes Passenger Transportation";
		break;
		case "4832":
		return "Inland Water Transportation";
		break;
		case "48321":
		return "Inland Water Transportation";
		break;
		case "483211":
		return "Inland Water Freight Transportation";
		break;
		case "483212":
		return "Inland Water Passenger Transportation";
		break;
		case "484":
		return "Truck Transportation";
		break;
		case "4841":
		return "General Freight Trucking";
		break;
		case "48411":
		return "General Freight Trucking, Local";
		break;
		case "484110":
		return "General Freight Trucking, Local";
		break;
		case "48412":
		return "General Freight Trucking, Long-Distance";
		break;
		case "484121":
		return "General Freight Trucking, Long-Distance, Truckload";
		break;
		case "484122":
		return "General Freight Trucking, Long-Distance, Less Than Truckload";
		break;
		case "4842":
		return "Specialized Freight Trucking";
		break;
		case "48421":
		return "Used Household and Office Goods Moving";
		break;
		case "484210":
		return "Used Household and Office Goods Moving";
		break;
		case "48422":
		return "Specialized Freight (except Used Goods) Trucking, Local";
		break;
		case "484220":
		return "Specialized Freight (except Used Goods) Trucking, Local";
		break;
		case "48423":
		return "Specialized Freight (except Used Goods) Trucking, Long-Distance";
		break;
		case "484230":
		return "Specialized Freight (except Used Goods) Trucking, Long-Distance";
		break;
		case "485":
		return "Transit and Ground Passenger Transportation";
		break;
		case "4851":
		return "Urban Transit Systems";
		break;
		case "48511":
		return "Urban Transit Systems";
		break;
		case "485111":
		return "Mixed Mode Transit Systems";
		break;
		case "485112":
		return "Commuter Rail Systems";
		break;
		case "485113":
		return "Bus and Other Motor Vehicle Transit Systems";
		break;
		case "485119":
		return "Other Urban Transit Systems";
		break;
		case "4852":
		return "Interurban and Rural Bus Transportation";
		break;
		case "48521":
		return "Interurban and Rural Bus Transportation";
		break;
		case "485210":
		return "Interurban and Rural Bus Transportation";
		break;
		case "4853":
		return "Taxi and Limousine Service";
		break;
		case "48531":
		return "Taxi Service";
		break;
		case "485310":
		return "Taxi Service";
		break;
		case "48532":
		return "Limousine Service";
		break;
		case "485320":
		return "Limousine Service";
		break;
		case "4854":
		return "School and Employee Bus Transportation";
		break;
		case "48541":
		return "School and Employee Bus Transportation";
		break;
		case "485410":
		return "School and Employee Bus Transportation";
		break;
		case "4855":
		return "Charter Bus Industry";
		break;
		case "48551":
		return "Charter Bus Industry";
		break;
		case "485510":
		return "Charter Bus Industry";
		break;
		case "4859":
		return "Other Transit and Ground Passenger Transportation";
		break;
		case "48599":
		return "Other Transit and Ground Passenger Transportation";
		break;
		case "485991":
		return "Special Needs Transportation";
		break;
		case "485999":
		return "All Other Transit and Ground Passenger Transportation";
		break;
		case "486":
		return "Pipeline Transportation";
		break;
		case "4861":
		return "Pipeline Transportation of Crude Oil";
		break;
		case "48611":
		return "Pipeline Transportation of Crude Oil";
		break;
		case "486110":
		return "Pipeline Transportation of Crude Oil";
		break;
		case "4862":
		return "Pipeline Transportation of Natural Gas";
		break;
		case "48621":
		return "Pipeline Transportation of Natural Gas";
		break;
		case "486210":
		return "Pipeline Transportation of Natural Gas";
		break;
		case "4869":
		return "Other Pipeline Transportation";
		break;
		case "48691":
		return "Pipeline Transportation of Refined Petroleum Products";
		break;
		case "486910":
		return "Pipeline Transportation of Refined Petroleum Products";
		break;
		case "48699":
		return "All Other Pipeline Transportation";
		break;
		case "486990":
		return "All Other Pipeline Transportation";
		break;
		case "487":
		return "Scenic and Sightseeing Transportation";
		break;
		case "4871":
		return "Scenic and Sightseeing Transportation, Land";
		break;
		case "48711":
		return "Scenic and Sightseeing Transportation, Land";
		break;
		case "487110":
		return "Scenic and Sightseeing Transportation, Land";
		break;
		case "4872":
		return "Scenic and Sightseeing Transportation, Water";
		break;
		case "48721":
		return "Scenic and Sightseeing Transportation, Water";
		break;
		case "487210":
		return "Scenic and Sightseeing Transportation, Water";
		break;
		case "4879":
		return "Scenic and Sightseeing Transportation, Other";
		break;
		case "48799":
		return "Scenic and Sightseeing Transportation, Other";
		break;
		case "487990":
		return "Scenic and Sightseeing Transportation, Other";
		break;
		case "488":
		return "Support Activities for Transportation";
		break;
		case "4881":
		return "Support Activities for Air Transportation";
		break;
		case "48811":
		return "Airport Operations";
		break;
		case "488111":
		return "Air Traffic Control";
		break;
		case "488119":
		return "Other Airport Operations";
		break;
		case "48819":
		return "Other Support Activities for Air Transportation";
		break;
		case "488190":
		return "Other Support Activities for Air Transportation";
		break;
		case "4882":
		return "Support Activities for Rail Transportation";
		break;
		case "48821":
		return "Support Activities for Rail Transportation";
		break;
		case "488210":
		return "Support Activities for Rail Transportation";
		break;
		case "4883":
		return "Support Activities for Water Transportation";
		break;
		case "48831":
		return "Port and Harbor Operations";
		break;
		case "488310":
		return "Port and Harbor Operations";
		break;
		case "48832":
		return "Marine Cargo Handling";
		break;
		case "488320":
		return "Marine Cargo Handling";
		break;
		case "48833":
		return "Navigational Services to Shipping";
		break;
		case "488330":
		return "Navigational Services to Shipping";
		break;
		case "48839":
		return "Other Support Activities for Water Transportation";
		break;
		case "488390":
		return "Other Support Activities for Water Transportation";
		break;
		case "4884":
		return "Support Activities for Road Transportation";
		break;
		case "48841":
		return "Motor Vehicle Towing";
		break;
		case "488410":
		return "Motor Vehicle Towing";
		break;
		case "48849":
		return "Other Support Activities for Road Transportation";
		break;
		case "488490":
		return "Other Support Activities for Road Transportation";
		break;
		case "4885":
		return "Freight Transportation Arrangement";
		break;
		case "48851":
		return "Freight Transportation Arrangement";
		break;
		case "488510":
		return "Freight Transportation Arrangement";
		break;
		case "4889":
		return "Other Support Activities for Transportation";
		break;
		case "48899":
		return "Other Support Activities for Transportation";
		break;
		case "488991":
		return "Packing and Crating";
		break;
		case "488999":
		return "All Other Support Activities for Transportation";
		break;
		case "491":
		return "Postal Service";
		break;
		case "4911":
		return "Postal Service";
		break;
		case "49111":
		return "Postal Service";
		break;
		case "491110":
		return "Postal Service";
		break;
		case "492":
		return "Couriers and Messengers";
		break;
		case "4921":
		return "Couriers and Express Delivery Services";
		break;
		case "49211":
		return "Couriers and Express Delivery Services";
		break;
		case "492110":
		return "Couriers and Express Delivery Services";
		break;
		case "4922":
		return "Local Messengers and Local Delivery";
		break;
		case "49221":
		return "Local Messengers and Local Delivery";
		break;
		case "492210":
		return "Local Messengers and Local Delivery";
		break;
		case "493":
		return "Warehousing and Storage";
		break;
		case "4931":
		return "Warehousing and Storage";
		break;
		case "49311":
		return "General Warehousing and Storage";
		break;
		case "493110":
		return "General Warehousing and Storage";
		break;
		case "49312":
		return "Refrigerated Warehousing and Storage";
		break;
		case "493120":
		return "Refrigerated Warehousing and Storage";
		break;
		case "49313":
		return "Farm Product Warehousing and Storage";
		break;
		case "493130":
		return "Farm Product Warehousing and Storage";
		break;
		case "49319":
		return "Other Warehousing and Storage";
		break;
		case "493190":
		return "Other Warehousing and Storage";
		break;
		case "51":
		return "Information";
		break;
		case "511":
		return "Publishing Industries (except Internet)";
		break;
		case "5111":
		return "Newspaper, Periodical, Book, and Directory Publishers";
		break;
		case "51111":
		return "Newspaper Publishers";
		break;
		case "511110":
		return "Newspaper Publishers";
		break;
		case "51112":
		return "Periodical Publishers";
		break;
		case "511120":
		return "Periodical Publishers";
		break;
		case "51113":
		return "Book Publishers";
		break;
		case "511130":
		return "Book Publishers";
		break;
		case "51114":
		return "Directory and Mailing List Publishers";
		break;
		case "511140":
		return "Directory and Mailing List Publishers";
		break;
		case "51119":
		return "Other Publishers";
		break;
		case "511191":
		return "Greeting Card Publishers";
		break;
		case "511199":
		return "All Other Publishers";
		break;
		case "5112":
		return "Software Publishers";
		break;
		case "51121":
		return "Software Publishers";
		break;
		case "511210":
		return "Software Publishers";
		break;
		case "512":
		return "Motion Picture and Sound Recording Industries";
		break;
		case "5121":
		return "Motion Picture and Video Industries";
		break;
		case "51211":
		return "Motion Picture and Video Production";
		break;
		case "512110":
		return "Motion Picture and Video Production";
		break;
		case "51212":
		return "Motion Picture and Video Distribution";
		break;
		case "512120":
		return "Motion Picture and Video Distribution";
		break;
		case "51213":
		return "Motion Picture and Video Exhibition";
		break;
		case "512131":
		return "Motion Picture Theaters (except Drive-Ins)";
		break;
		case "512132":
		return "Drive-In Motion Picture Theaters";
		break;
		case "51219":
		return "Postproduction Services and Other Motion Picture and Video Industries";
		break;
		case "512191":
		return "Teleproduction and Other Postproduction Services";
		break;
		case "512199":
		return "Other Motion Picture and Video Industries";
		break;
		case "5122":
		return "Sound Recording Industries";
		break;
		case "51221":
		return "Record Production";
		break;
		case "512210":
		return "Record Production";
		break;
		case "51222":
		return "Integrated Record Production/Distribution";
		break;
		case "512220":
		return "Integrated Record Production/Distribution";
		break;
		case "51223":
		return "Music Publishers";
		break;
		case "512230":
		return "Music Publishers";
		break;
		case "51224":
		return "Sound Recording Studios";
		break;
		case "512240":
		return "Sound Recording Studios";
		break;
		case "51229":
		return "Other Sound Recording Industries";
		break;
		case "512290":
		return "Other Sound Recording Industries";
		break;
		case "515":
		return "Broadcasting (except Internet)";
		break;
		case "5151":
		return "Radio and Television Broadcasting";
		break;
		case "51511":
		return "Radio Broadcasting";
		break;
		case "515111":
		return "Radio Networks";
		break;
		case "515112":
		return "Radio Stations";
		break;
		case "51512":
		return "Television Broadcasting";
		break;
		case "515120":
		return "Television Broadcasting";
		break;
		case "5152":
		return "Cable and Other Subscription Programming";
		break;
		case "51521":
		return "Cable and Other Subscription Programming";
		break;
		case "515210":
		return "Cable and Other Subscription Programming";
		break;
		case "517":
		return "Telecommunications";
		break;
		case "5171":
		return "Wired Telecommunications Carriers";
		break;
		case "51711":
		return "Wired Telecommunications Carriers";
		break;
		case "517110":
		return "Wired Telecommunications Carriers";
		break;
		case "5172":
		return "Wireless Telecommunications Carriers (except Satellite)";
		break;
		case "51721":
		return "Wireless Telecommunications Carriers (except Satellite)";
		break;
		case "517210":
		return "Wireless Telecommunications Carriers (except Satellite)";
		break;
		case "5174":
		return "Satellite Telecommunications";
		break;
		case "51741":
		return "Satellite Telecommunications";
		break;
		case "517410":
		return "Satellite Telecommunications";
		break;
		case "5179":
		return "Other Telecommunications";
		break;
		case "51791":
		return "Other Telecommunications";
		break;
		case "517911":
		return "Telecommunications Resellers";
		break;
		case "517919":
		return "All Other Telecommunications";
		break;
		case "518":
		return "Data Processing, Hosting and Related Services";
		break;
		case "5182":
		return "Data Processing, Hosting, and Related Services";
		break;
		case "51821":
		return "Data Processing, Hosting, and Related Services";
		break;
		case "518210":
		return "Data Processing, Hosting, and Related Services";
		break;
		case "519":
		return "Other Information Services";
		break;
		case "5191":
		return "Other Information Services";
		break;
		case "51911":
		return "News Syndicates";
		break;
		case "519110":
		return "News Syndicates";
		break;
		case "51912":
		return "Libraries and Archives";
		break;
		case "519120":
		return "Libraries and Archives";
		break;
		case "51913":
		return "Internet Publishing and Broadcasting and Web Search Portals";
		break;
		case "519130":
		return "Internet Publishing and Broadcasting and Web Search Portals";
		break;
		case "51919":
		return "All Other Information Services";
		break;
		case "519190":
		return "All Other Information Services";
		break;
		case "52":
		return "Finance and Insurance";
		break;
		case "521":
		return "Monetary Authorities-Central Bank";
		break;
		case "5211":
		return "Monetary Authorities-Central Bank";
		break;
		case "52111":
		return "Monetary Authorities-Central Bank";
		break;
		case "521110":
		return "Monetary Authorities-Central Bank";
		break;
		case "522":
		return "Credit Intermediation and Related Activities";
		break;
		case "5221":
		return "Depository Credit Intermediation";
		break;
		case "52211":
		return "Commercial Banking";
		break;
		case "522110":
		return "Commercial Banking";
		break;
		case "52212":
		return "Savings Institutions";
		break;
		case "522120":
		return "Savings Institutions";
		break;
		case "52213":
		return "Credit Unions";
		break;
		case "522130":
		return "Credit Unions";
		break;
		case "52219":
		return "Other Depository Credit Intermediation";
		break;
		case "522190":
		return "Other Depository Credit Intermediation";
		break;
		case "5222":
		return "Nondepository Credit Intermediation";
		break;
		case "52221":
		return "Credit Card Issuing";
		break;
		case "522210":
		return "Credit Card Issuing";
		break;
		case "52222":
		return "Sales Financing";
		break;
		case "522220":
		return "Sales Financing";
		break;
		case "52229":
		return "Other Nondepository Credit Intermediation";
		break;
		case "522291":
		return "Consumer Lending";
		break;
		case "522292":
		return "Real Estate Credit";
		break;
		case "522293":
		return "International Trade Financing";
		break;
		case "522294":
		return "Secondary Market Financing";
		break;
		case "522298":
		return "All Other Nondepository Credit Intermediation";
		break;
		case "5223":
		return "Activities Related to Credit Intermediation";
		break;
		case "52231":
		return "Mortgage and Nonmortgage Loan Brokers";
		break;
		case "522310":
		return "Mortgage and Nonmortgage Loan Brokers";
		break;
		case "52232":
		return "Financial Transactions Processing, Reserve, and Clearinghouse Activities";
		break;
		case "522320":
		return "Financial Transactions Processing, Reserve, and Clearinghouse Activities";
		break;
		case "52239":
		return "Other Activities Related to Credit Intermediation";
		break;
		case "522390":
		return "Other Activities Related to Credit Intermediation";
		break;
		case "523":
		return "Securities, Commodity Contracts, and Other Financial Investments and Related Activities";
		break;
		case "5231":
		return "Securities and Commodity Contracts Intermediation and Brokerage";
		break;
		case "52311":
		return "Investment Banking and Securities Dealing";
		break;
		case "523110":
		return "Investment Banking and Securities Dealing";
		break;
		case "52312":
		return "Securities Brokerage";
		break;
		case "523120":
		return "Securities Brokerage";
		break;
		case "52313":
		return "Commodity Contracts Dealing";
		break;
		case "523130":
		return "Commodity Contracts Dealing";
		break;
		case "52314":
		return "Commodity Contracts Brokerage";
		break;
		case "523140":
		return "Commodity Contracts Brokerage";
		break;
		case "5232":
		return "Securities and Commodity Exchanges";
		break;
		case "52321":
		return "Securities and Commodity Exchanges";
		break;
		case "523210":
		return "Securities and Commodity Exchanges";
		break;
		case "5239":
		return "Other Financial Investment Activities";
		break;
		case "52391":
		return "Miscellaneous Intermediation";
		break;
		case "523910":
		return "Miscellaneous Intermediation";
		break;
		case "52392":
		return "Portfolio Management";
		break;
		case "523920":
		return "Portfolio Management";
		break;
		case "52393":
		return "Investment Advice";
		break;
		case "523930":
		return "Investment Advice";
		break;
		case "52399":
		return "All Other Financial Investment Activities";
		break;
		case "523991":
		return "Trust, Fiduciary, and Custody Activities";
		break;
		case "523999":
		return "Miscellaneous Financial Investment Activities";
		break;
		case "524":
		return "Insurance Carriers and Related Activities";
		break;
		case "5241":
		return "Insurance Carriers";
		break;
		case "52411":
		return "Direct Life, Health, and Medical Insurance Carriers";
		break;
		case "524113":
		return "Direct Life Insurance Carriers";
		break;
		case "524114":
		return "Direct Health and Medical Insurance Carriers";
		break;
		case "52412":
		return "Direct Insurance (except Life, Health, and Medical) Carriers";
		break;
		case "524126":
		return "Direct Property and Casualty Insurance Carriers";
		break;
		case "524127":
		return "Direct Title Insurance Carriers";
		break;
		case "524128":
		return "Other Direct Insurance (except Life, Health, and Medical) Carriers";
		break;
		case "52413":
		return "Reinsurance Carriers";
		break;
		case "524130":
		return "Reinsurance Carriers";
		break;
		case "5242":
		return "Agencies, Brokerages, and Other Insurance Related Activities";
		break;
		case "52421":
		return "Insurance Agencies and Brokerages";
		break;
		case "524210":
		return "Insurance Agencies and Brokerages";
		break;
		case "52429":
		return "Other Insurance Related Activities";
		break;
		case "524291":
		return "Claims Adjusting";
		break;
		case "524292":
		return "Third Party Administration of Insurance and Pension Funds";
		break;
		case "524298":
		return "All Other Insurance Related Activities";
		break;
		case "525":
		return "Funds, Trusts, and Other Financial Vehicles";
		break;
		case "5251":
		return "Insurance and Employee Benefit Funds";
		break;
		case "52511":
		return "Pension Funds";
		break;
		case "525110":
		return "Pension Funds";
		break;
		case "52512":
		return "Health and Welfare Funds";
		break;
		case "525120":
		return "Health and Welfare Funds";
		break;
		case "52519":
		return "Other Insurance Funds";
		break;
		case "525190":
		return "Other Insurance Funds";
		break;
		case "5259":
		return "Other Investment Pools and Funds";
		break;
		case "52591":
		return "Open-End Investment Funds";
		break;
		case "525910":
		return "Open-End Investment Funds";
		break;
		case "52592":
		return "Trusts, Estates, and Agency Accounts";
		break;
		case "525920":
		return "Trusts, Estates, and Agency Accounts";
		break;
		case "52599":
		return "Other Financial Vehicles";
		break;
		case "525990":
		return "Other Financial Vehicles";
		break;
		case "53":
		return "Real Estate and Rental and Leasing";
		break;
		case "531":
		return "Real Estate";
		break;
		case "5311":
		return "Lessors of Real Estate";
		break;
		case "53111":
		return "Lessors of Residential Buildings and Dwellings";
		break;
		case "531110":
		return "Lessors of Residential Buildings and Dwellings";
		break;
		case "53112":
		return "Lessors of Nonresidential Buildings (except Miniwarehouses)";
		break;
		case "531120":
		return "Lessors of Nonresidential Buildings (except Miniwarehouses)";
		break;
		case "53113":
		return "Lessors of Miniwarehouses and Self-Storage Units";
		break;
		case "531130":
		return "Lessors of Miniwarehouses and Self-Storage Units";
		break;
		case "53119":
		return "Lessors of Other Real Estate Property";
		break;
		case "531190":
		return "Lessors of Other Real Estate Property";
		break;
		case "5312":
		return "Offices of Real Estate Agents and Brokers";
		break;
		case "53121":
		return "Offices of Real Estate Agents and Brokers";
		break;
		case "531210":
		return "Offices of Real Estate Agents and Brokers";
		break;
		case "5313":
		return "Activities Related to Real Estate";
		break;
		case "53131":
		return "Real Estate Property Managers";
		break;
		case "531311":
		return "Residential Property Managers";
		break;
		case "531312":
		return "Nonresidential Property Managers";
		break;
		case "53132":
		return "Offices of Real Estate Appraisers";
		break;
		case "531320":
		return "Offices of Real Estate Appraisers";
		break;
		case "53139":
		return "Other Activities Related to Real Estate";
		break;
		case "531390":
		return "Other Activities Related to Real Estate";
		break;
		case "532":
		return "Rental and Leasing Services";
		break;
		case "5321":
		return "Automotive Equipment Rental and Leasing";
		break;
		case "53211":
		return "Passenger Car Rental and Leasing";
		break;
		case "532111":
		return "Passenger Car Rental";
		break;
		case "532112":
		return "Passenger Car Leasing";
		break;
		case "53212":
		return "Truck, Utility Trailer, and RV (Recreational Vehicle) Rental and Leasing";
		break;
		case "532120":
		return "Truck, Utility Trailer, and RV (Recreational Vehicle) Rental and Leasing";
		break;
		case "5322":
		return "Consumer Goods Rental";
		break;
		case "53221":
		return "Consumer Electronics and Appliances Rental";
		break;
		case "532210":
		return "Consumer Electronics and Appliances Rental";
		break;
		case "53222":
		return "Formal Wear and Costume Rental";
		break;
		case "532220":
		return "Formal Wear and Costume Rental";
		break;
		case "53223":
		return "Video Tape and Disc Rental";
		break;
		case "532230":
		return "Video Tape and Disc Rental";
		break;
		case "53229":
		return "Other Consumer Goods Rental";
		break;
		case "532291":
		return "Home Health Equipment Rental";
		break;
		case "532292":
		return "Recreational Goods Rental";
		break;
		case "532299":
		return "All Other Consumer Goods Rental";
		break;
		case "5323":
		return "General Rental Centers";
		break;
		case "53231":
		return "General Rental Centers";
		break;
		case "532310":
		return "General Rental Centers";
		break;
		case "5324":
		return "Commercial and Industrial Machinery and Equipment Rental and Leasing";
		break;
		case "53241":
		return "Construction, Transportation, Mining, and Forestry Machinery and Equipment Rental and Leasing";
		break;
		case "532411":
		return "Commercial Air, Rail, and Water Transportation Equipment Rental and Leasing";
		break;
		case "532412":
		return "Construction, Mining, and Forestry Machinery and Equipment Rental and Leasing";
		break;
		case "53242":
		return "Office Machinery and Equipment Rental and Leasing";
		break;
		case "532420":
		return "Office Machinery and Equipment Rental and Leasing";
		break;
		case "53249":
		return "Other Commercial and Industrial Machinery and Equipment Rental and Leasing";
		break;
		case "532490":
		return "Other Commercial and Industrial Machinery and Equipment Rental and Leasing";
		break;
		case "533":
		return "Lessors of Nonfinancial Intangible Assets (except Copyrighted Works)";
		break;
		case "5331":
		return "Lessors of Nonfinancial Intangible Assets (except Copyrighted Works)";
		break;
		case "53311":
		return "Lessors of Nonfinancial Intangible Assets (except Copyrighted Works)";
		break;
		case "533110":
		return "Lessors of Nonfinancial Intangible Assets (except Copyrighted Works)";
		break;
		case "54":
		return "Professional, Scientific, and Technical Services";
		break;
		case "541":
		return "Professional, Scientific, and Technical Services";
		break;
		case "5411":
		return "Legal Services";
		break;
		case "54111":
		return "Offices of Lawyers";
		break;
		case "541110":
		return "Offices of Lawyers";
		break;
		case "54112":
		return "Offices of Notaries";
		break;
		case "541120":
		return "Offices of Notaries";
		break;
		case "54119":
		return "Other Legal Services";
		break;
		case "541191":
		return "Title Abstract and Settlement Offices";
		break;
		case "541199":
		return "All Other Legal Services";
		break;
		case "5412":
		return "Accounting, Tax Preparation, Bookkeeping, and Payroll Services";
		break;
		case "54121":
		return "Accounting, Tax Preparation, Bookkeeping, and Payroll Services";
		break;
		case "541211":
		return "Offices of Certified Public Accountants";
		break;
		case "541213":
		return "Tax Preparation Services";
		break;
		case "541214":
		return "Payroll Services";
		break;
		case "541219":
		return "Other Accounting Services";
		break;
		case "5413":
		return "Architectural, Engineering, and Related Services";
		break;
		case "54131":
		return "Architectural Services";
		break;
		case "541310":
		return "Architectural Services";
		break;
		case "54132":
		return "Landscape Architectural Services";
		break;
		case "541320":
		return "Landscape Architectural Services";
		break;
		case "54133":
		return "Engineering Services";
		break;
		case "541330":
		return "Engineering Services";
		break;
		case "54134":
		return "Drafting Services";
		break;
		case "541340":
		return "Drafting Services";
		break;
		case "54135":
		return "Building Inspection Services";
		break;
		case "541350":
		return "Building Inspection Services";
		break;
		case "54136":
		return "Geophysical Surveying and Mapping Services";
		break;
		case "541360":
		return "Geophysical Surveying and Mapping Services";
		break;
		case "54137":
		return "Surveying and Mapping (except Geophysical) Services";
		break;
		case "541370":
		return "Surveying and Mapping (except Geophysical) Services";
		break;
		case "54138":
		return "Testing Laboratories";
		break;
		case "541380":
		return "Testing Laboratories";
		break;
		case "5414":
		return "Specialized Design Services";
		break;
		case "54141":
		return "Interior Design Services";
		break;
		case "541410":
		return "Interior Design Services";
		break;
		case "54142":
		return "Industrial Design Services";
		break;
		case "541420":
		return "Industrial Design Services";
		break;
		case "54143":
		return "Graphic Design Services";
		break;
		case "541430":
		return "Graphic Design Services";
		break;
		case "54149":
		return "Other Specialized Design Services";
		break;
		case "541490":
		return "Other Specialized Design Services";
		break;
		case "5415":
		return "Computer Systems Design and Related Services";
		break;
		case "54151":
		return "Computer Systems Design and Related Services";
		break;
		case "541511":
		return "Custom Computer Programming Services";
		break;
		case "541512":
		return "Computer Systems Design Services";
		break;
		case "541513":
		return "Computer Facilities Management Services";
		break;
		case "541519":
		return "Other Computer Related Services";
		break;
		case "5416":
		return "Management, Scientific, and Technical Consulting Services";
		break;
		case "54161":
		return "Management Consulting Services";
		break;
		case "541611":
		return "Administrative Management and General Management Consulting Services";
		break;
		case "541612":
		return "Human Resources Consulting Services";
		break;
		case "541613":
		return "Marketing Consulting Services";
		break;
		case "541614":
		return "Process, Physical Distribution, and Logistics Consulting Services";
		break;
		case "541618":
		return "Other Management Consulting Services";
		break;
		case "54162":
		return "Environmental Consulting Services";
		break;
		case "541620":
		return "Environmental Consulting Services";
		break;
		case "54169":
		return "Other Scientific and Technical Consulting Services";
		break;
		case "541690":
		return "Other Scientific and Technical Consulting Services";
		break;
		case "5417":
		return "Scientific Research and Development Services";
		break;
		case "54171":
		return "Research and Development in the Physical, Engineering, and Life Sciences";
		break;
		case "541711":
		return "Research and Development in Biotechnology";
		break;
		case "541712":
		return "Research and Development in the Physical, Engineering, and Life Sciences (except Biotechnology)";
		break;
		case "54172":
		return "Research and Development in the Social Sciences and Humanities";
		break;
		case "541720":
		return "Research and Development in the Social Sciences and Humanities";
		break;
		case "5418":
		return "Advertising, Public Relations, and Related Services";
		break;
		case "54181":
		return "Advertising Agencies";
		break;
		case "541810":
		return "Advertising Agencies";
		break;
		case "54182":
		return "Public Relations Agencies";
		break;
		case "541820":
		return "Public Relations Agencies";
		break;
		case "54183":
		return "Media Buying Agencies";
		break;
		case "541830":
		return "Media Buying Agencies";
		break;
		case "54184":
		return "Media Representatives";
		break;
		case "541840":
		return "Media Representatives";
		break;
		case "54185":
		return "Display Advertising";
		break;
		case "541850":
		return "Display Advertising";
		break;
		case "54186":
		return "Direct Mail Advertising";
		break;
		case "541860":
		return "Direct Mail Advertising";
		break;
		case "54187":
		return "Advertising Material Distribution Services";
		break;
		case "541870":
		return "Advertising Material Distribution Services";
		break;
		case "54189":
		return "Other Services Related to Advertising";
		break;
		case "541890":
		return "Other Services Related to Advertising";
		break;
		case "5419":
		return "Other Professional, Scientific, and Technical Services";
		break;
		case "54191":
		return "Marketing Research and Public Opinion Polling";
		break;
		case "541910":
		return "Marketing Research and Public Opinion Polling";
		break;
		case "54192":
		return "Photographic Services";
		break;
		case "541921":
		return "Photography Studios, Portrait";
		break;
		case "541922":
		return "Commercial Photography";
		break;
		case "54193":
		return "Translation and Interpretation Services";
		break;
		case "541930":
		return "Translation and Interpretation Services";
		break;
		case "54194":
		return "Veterinary Services";
		break;
		case "541940":
		return "Veterinary Services";
		break;
		case "54199":
		return "All Other Professional, Scientific, and Technical Services";
		break;
		case "541990":
		return "All Other Professional, Scientific, and Technical Services";
		break;
		case "55":
		return "Management of Companies and Enterprises";
		break;
		case "551":
		return "Management of Companies and Enterprises";
		break;
		case "5511":
		return "Management of Companies and Enterprises";
		break;
		case "55111":
		return "Management of Companies and Enterprises";
		break;
		case "551111":
		return "Offices of Bank Holding Companies";
		break;
		case "551112":
		return "Offices of Other Holding Companies";
		break;
		case "551114":
		return "Corporate, Subsidiary, and Regional Managing Offices";
		break;
		case "56":
		return "Administrative and Support and Waste Management and Remediation Services";
		break;
		case "561":
		return "Administrative and Support Services";
		break;
		case "5611":
		return "Office Administrative Services";
		break;
		case "56111":
		return "Office Administrative Services";
		break;
		case "561110":
		return "Office Administrative Services";
		break;
		case "5612":
		return "Facilities Support Services";
		break;
		case "56121":
		return "Facilities Support Services";
		break;
		case "561210":
		return "Facilities Support Services";
		break;
		case "5613":
		return "Employment Services";
		break;
		case "56131":
		return "Employment Placement Agencies and Executive Search Services";
		break;
		case "561311":
		return "Employment Placement Agencies";
		break;
		case "561312":
		return "Executive Search Services";
		break;
		case "56132":
		return "Temporary Help Services";
		break;
		case "561320":
		return "Temporary Help Services";
		break;
		case "56133":
		return "Professional Employer Organizations";
		break;
		case "561330":
		return "Professional Employer Organizations";
		break;
		case "5614":
		return "Business Support Services";
		break;
		case "56141":
		return "Document Preparation Services";
		break;
		case "561410":
		return "Document Preparation Services";
		break;
		case "56142":
		return "Telephone Call Centers";
		break;
		case "561421":
		return "Telephone Answering Services";
		break;
		case "561422":
		return "Telemarketing Bureaus and Other Contact Centers";
		break;
		case "56143":
		return "Business Service Centers";
		break;
		case "561431":
		return "Private Mail Centers";
		break;
		case "561439":
		return "Other Business Service Centers (including Copy Shops)";
		break;
		case "56144":
		return "Collection Agencies";
		break;
		case "561440":
		return "Collection Agencies";
		break;
		case "56145":
		return "Credit Bureaus";
		break;
		case "561450":
		return "Credit Bureaus";
		break;
		case "56149":
		return "Other Business Support Services";
		break;
		case "561491":
		return "Repossession Services";
		break;
		case "561492":
		return "Court Reporting and Stenotype Services";
		break;
		case "561499":
		return "All Other Business Support Services";
		break;
		case "5615":
		return "Travel Arrangement and Reservation Services";
		break;
		case "56151":
		return "Travel Agencies";
		break;
		case "561510":
		return "Travel Agencies";
		break;
		case "56152":
		return "Tour Operators";
		break;
		case "561520":
		return "Tour Operators";
		break;
		case "56159":
		return "Other Travel Arrangement and Reservation Services";
		break;
		case "561591":
		return "Convention and Visitors Bureaus";
		break;
		case "561599":
		return "All Other Travel Arrangement and Reservation Services";
		break;
		case "5616":
		return "Investigation and Security Services";
		break;
		case "56161":
		return "Investigation, Guard, and Armored Car Services";
		break;
		case "561611":
		return "Investigation Services";
		break;
		case "561612":
		return "Security Guards and Patrol Services";
		break;
		case "561613":
		return "Armored Car Services";
		break;
		case "56162":
		return "Security Systems Services";
		break;
		case "561621":
		return "Security Systems Services (except Locksmiths)";
		break;
		case "561622":
		return "Locksmiths";
		break;
		case "5617":
		return "Services to Buildings and Dwellings";
		break;
		case "56171":
		return "Exterminating and Pest Control Services";
		break;
		case "561710":
		return "Exterminating and Pest Control Services";
		break;
		case "56172":
		return "Janitorial Services";
		break;
		case "561720":
		return "Janitorial Services";
		break;
		case "56173":
		return "Landscaping Services";
		break;
		case "561730":
		return "Landscaping Services";
		break;
		case "56174":
		return "Carpet and Upholstery Cleaning Services";
		break;
		case "561740":
		return "Carpet and Upholstery Cleaning Services";
		break;
		case "56179":
		return "Other Services to Buildings and Dwellings";
		break;
		case "561790":
		return "Other Services to Buildings and Dwellings";
		break;
		case "5619":
		return "Other Support Services";
		break;
		case "56191":
		return "Packaging and Labeling Services";
		break;
		case "561910":
		return "Packaging and Labeling Services";
		break;
		case "56192":
		return "Convention and Trade Show Organizers";
		break;
		case "561920":
		return "Convention and Trade Show Organizers";
		break;
		case "56199":
		return "All Other Support Services";
		break;
		case "561990":
		return "All Other Support Services";
		break;
		case "562":
		return "Waste Management and Remediation Services";
		break;
		case "5621":
		return "Waste Collection";
		break;
		case "56211":
		return "Waste Collection";
		break;
		case "562111":
		return "Solid Waste Collection";
		break;
		case "562112":
		return "Hazardous Waste Collection";
		break;
		case "562119":
		return "Other Waste Collection";
		break;
		case "5622":
		return "Waste Treatment and Disposal";
		break;
		case "56221":
		return "Waste Treatment and Disposal";
		break;
		case "562211":
		return "Hazardous Waste Treatment and Disposal";
		break;
		case "562212":
		return "Solid Waste Landfill";
		break;
		case "562213":
		return "Solid Waste Combustors and Incinerators";
		break;
		case "562219":
		return "Other Nonhazardous Waste Treatment and Disposal";
		break;
		case "5629":
		return "Remediation and Other Waste Management Services";
		break;
		case "56291":
		return "Remediation Services";
		break;
		case "562910":
		return "Remediation Services";
		break;
		case "56292":
		return "Materials Recovery Facilities";
		break;
		case "562920":
		return "Materials Recovery Facilities";
		break;
		case "56299":
		return "All Other Waste Management Services";
		break;
		case "562991":
		return "Septic Tank and Related Services";
		break;
		case "562998":
		return "All Other Miscellaneous Waste Management Services";
		break;
		case "61":
		return "Educational Services";
		break;
		case "611":
		return "Educational Services";
		break;
		case "6111":
		return "Elementary and Secondary Schools";
		break;
		case "61111":
		return "Elementary and Secondary Schools";
		break;
		case "611110":
		return "Elementary and Secondary Schools";
		break;
		case "6112":
		return "Junior Colleges";
		break;
		case "61121":
		return "Junior Colleges";
		break;
		case "611210":
		return "Junior Colleges";
		break;
		case "6113":
		return "Colleges, Universities, and Professional Schools";
		break;
		case "61131":
		return "Colleges, Universities, and Professional Schools";
		break;
		case "611310":
		return "Colleges, Universities, and Professional Schools";
		break;
		case "6114":
		return "Business Schools and Computer and Management Training";
		break;
		case "61141":
		return "Business and Secretarial Schools";
		break;
		case "611410":
		return "Business and Secretarial Schools";
		break;
		case "61142":
		return "Computer Training";
		break;
		case "611420":
		return "Computer Training";
		break;
		case "61143":
		return "Professional and Management Development Training";
		break;
		case "611430":
		return "Professional and Management Development Training";
		break;
		case "6115":
		return "Technical and Trade Schools";
		break;
		case "61151":
		return "Technical and Trade Schools";
		break;
		case "611511":
		return "Cosmetology and Barber Schools";
		break;
		case "611512":
		return "Flight Training";
		break;
		case "611513":
		return "Apprenticeship Training";
		break;
		case "611519":
		return "Other Technical and Trade Schools";
		break;
		case "6116":
		return "Other Schools and Instruction";
		break;
		case "61161":
		return "Fine Arts Schools";
		break;
		case "611610":
		return "Fine Arts Schools";
		break;
		case "61162":
		return "Sports and Recreation Instruction";
		break;
		case "611620":
		return "Sports and Recreation Instruction";
		break;
		case "61163":
		return "Language Schools";
		break;
		case "611630":
		return "Language Schools";
		break;
		case "61169":
		return "All Other Schools and Instruction";
		break;
		case "611691":
		return "Exam Preparation and Tutoring";
		break;
		case "611692":
		return "Automobile Driving Schools";
		break;
		case "611699":
		return "All Other Miscellaneous Schools and Instruction";
		break;
		case "6117":
		return "Educational Support Services";
		break;
		case "61171":
		return "Educational Support Services";
		break;
		case "611710":
		return "Educational Support Services";
		break;
		case "62":
		return "Health Care and Social Assistance";
		break;
		case "621":
		return "Ambulatory Health Care Services";
		break;
		case "6211":
		return "Offices of Physicians";
		break;
		case "62111":
		return "Offices of Physicians";
		break;
		case "621111":
		return "Offices of Physicians (except Mental Health Specialists)";
		break;
		case "621112":
		return "Offices of Physicians, Mental Health Specialists";
		break;
		case "6212":
		return "Offices of Dentists";
		break;
		case "62121":
		return "Offices of Dentists";
		break;
		case "621210":
		return "Offices of Dentists";
		break;
		case "6213":
		return "Offices of Other Health Practitioners";
		break;
		case "62131":
		return "Offices of Chiropractors";
		break;
		case "621310":
		return "Offices of Chiropractors";
		break;
		case "62132":
		return "Offices of Optometrists";
		break;
		case "621320":
		return "Offices of Optometrists";
		break;
		case "62133":
		return "Offices of Mental Health Practitioners (except Physicians)";
		break;
		case "621330":
		return "Offices of Mental Health Practitioners (except Physicians)";
		break;
		case "62134":
		return "Offices of Physical, Occupational and Speech Therapists, and Audiologists";
		break;
		case "621340":
		return "Offices of Physical, Occupational and Speech Therapists, and Audiologists";
		break;
		case "62139":
		return "Offices of All Other Health Practitioners";
		break;
		case "621391":
		return "Offices of Podiatrists";
		break;
		case "621399":
		return "Offices of All Other Miscellaneous Health Practitioners";
		break;
		case "6214":
		return "Outpatient Care Centers";
		break;
		case "62141":
		return "Family Planning Centers";
		break;
		case "621410":
		return "Family Planning Centers";
		break;
		case "62142":
		return "Outpatient Mental Health and Substance Abuse Centers";
		break;
		case "621420":
		return "Outpatient Mental Health and Substance Abuse Centers";
		break;
		case "62149":
		return "Other Outpatient Care Centers";
		break;
		case "621491":
		return "HMO Medical Centers";
		break;
		case "621492":
		return "Kidney Dialysis Centers";
		break;
		case "621493":
		return "Freestanding Ambulatory Surgical and Emergency Centers";
		break;
		case "621498":
		return "All Other Outpatient Care Centers";
		break;
		case "6215":
		return "Medical and Diagnostic Laboratories";
		break;
		case "62151":
		return "Medical and Diagnostic Laboratories";
		break;
		case "621511":
		return "Medical Laboratories";
		break;
		case "621512":
		return "Diagnostic Imaging Centers";
		break;
		case "6216":
		return "Home Health Care Services";
		break;
		case "62161":
		return "Home Health Care Services";
		break;
		case "621610":
		return "Home Health Care Services";
		break;
		case "6219":
		return "Other Ambulatory Health Care Services";
		break;
		case "62191":
		return "Ambulance Services";
		break;
		case "621910":
		return "Ambulance Services";
		break;
		case "62199":
		return "All Other Ambulatory Health Care Services";
		break;
		case "621991":
		return "Blood and Organ Banks";
		break;
		case "621999":
		return "All Other Miscellaneous Ambulatory Health Care Services";
		break;
		case "622":
		return "Hospitals";
		break;
		case "6221":
		return "General Medical and Surgical Hospitals";
		break;
		case "62211":
		return "General Medical and Surgical Hospitals";
		break;
		case "622110":
		return "General Medical and Surgical Hospitals";
		break;
		case "6222":
		return "Psychiatric and Substance Abuse Hospitals";
		break;
		case "62221":
		return "Psychiatric and Substance Abuse Hospitals";
		break;
		case "622210":
		return "Psychiatric and Substance Abuse Hospitals";
		break;
		case "6223":
		return "Specialty (except Psychiatric and Substance Abuse) Hospitals";
		break;
		case "62231":
		return "Specialty (except Psychiatric and Substance Abuse) Hospitals";
		break;
		case "622310":
		return "Specialty (except Psychiatric and Substance Abuse) Hospitals";
		break;
		case "623":
		return "Nursing and Residential Care Facilities";
		break;
		case "6231":
		return "Nursing Care Facilities";
		break;
		case "62311":
		return "Nursing Care Facilities";
		break;
		case "623110":
		return "Nursing Care Facilities";
		break;
		case "6232":
		return "Residential Mental Retardation, Mental Health and Substance Abuse Facilities";
		break;
		case "62321":
		return "Residential Mental Retardation Facilities";
		break;
		case "623210":
		return "Residential Mental Retardation Facilities";
		break;
		case "62322":
		return "Residential Mental Health and Substance Abuse Facilities";
		break;
		case "623220":
		return "Residential Mental Health and Substance Abuse Facilities";
		break;
		case "6233":
		return "Community Care Facilities for the Elderly";
		break;
		case "62331":
		return "Community Care Facilities for the Elderly";
		break;
		case "623311":
		return "Continuing Care Retirement Communities";
		break;
		case "623312":
		return "Homes for the Elderly";
		break;
		case "6239":
		return "Other Residential Care Facilities";
		break;
		case "62399":
		return "Other Residential Care Facilities";
		break;
		case "623990":
		return "Other Residential Care Facilities";
		break;
		case "624":
		return "Social Assistance";
		break;
		case "6241":
		return "Individual and Family Services";
		break;
		case "62411":
		return "Child and Youth Services";
		break;
		case "624110":
		return "Child and Youth Services";
		break;
		case "62412":
		return "Services for the Elderly and Persons with Disabilities";
		break;
		case "624120":
		return "Services for the Elderly and Persons with Disabilities";
		break;
		case "62419":
		return "Other Individual and Family Services";
		break;
		case "624190":
		return "Other Individual and Family Services";
		break;
		case "6242":
		return "Community Food and Housing, and Emergency and Other Relief Services";
		break;
		case "62421":
		return "Community Food Services";
		break;
		case "624210":
		return "Community Food Services";
		break;
		case "62422":
		return "Community Housing Services";
		break;
		case "624221":
		return "Temporary Shelters";
		break;
		case "624229":
		return "Other Community Housing Services";
		break;
		case "62423":
		return "Emergency and Other Relief Services";
		break;
		case "624230":
		return "Emergency and Other Relief Services";
		break;
		case "6243":
		return "Vocational Rehabilitation Services";
		break;
		case "62431":
		return "Vocational Rehabilitation Services";
		break;
		case "624310":
		return "Vocational Rehabilitation Services";
		break;
		case "6244":
		return "Child Day Care Services";
		break;
		case "62441":
		return "Child Day Care Services";
		break;
		case "624410":
		return "Child Day Care Services";
		break;
		case "71":
		return "Arts, Entertainment, and Recreation";
		break;
		case "711":
		return "Performing Arts, Spectator Sports, and Related Industries";
		break;
		case "7111":
		return "Performing Arts Companies";
		break;
		case "71111":
		return "Theater Companies and Dinner Theaters";
		break;
		case "711110":
		return "Theater Companies and Dinner Theaters";
		break;
		case "71112":
		return "Dance Companies";
		break;
		case "711120":
		return "Dance Companies";
		break;
		case "71113":
		return "Musical Groups and Artists";
		break;
		case "711130":
		return "Musical Groups and Artists";
		break;
		case "71119":
		return "Other Performing Arts Companies";
		break;
		case "711190":
		return "Other Performing Arts Companies";
		break;
		case "7112":
		return "Spectator Sports";
		break;
		case "71121":
		return "Spectator Sports";
		break;
		case "711211":
		return "Sports Teams and Clubs";
		break;
		case "711212":
		return "Racetracks";
		break;
		case "711219":
		return "Other Spectator Sports";
		break;
		case "7113":
		return "Promoters of Performing Arts, Sports, and Similar Events";
		break;
		case "71131":
		return "Promoters of Performing Arts, Sports, and Similar Events with Facilities";
		break;
		case "711310":
		return "Promoters of Performing Arts, Sports, and Similar Events with Facilities";
		break;
		case "71132":
		return "Promoters of Performing Arts, Sports, and Similar Events without Facilities";
		break;
		case "711320":
		return "Promoters of Performing Arts, Sports, and Similar Events without Facilities";
		break;
		case "7114":
		return "Agents and Managers for Artists, Athletes, Entertainers, and Other Public Figures";
		break;
		case "71141":
		return "Agents and Managers for Artists, Athletes, Entertainers, and Other Public Figures";
		break;
		case "711410":
		return "Agents and Managers for Artists, Athletes, Entertainers, and Other Public Figures";
		break;
		case "7115":
		return "Independent Artists, Writers, and Performers";
		break;
		case "71151":
		return "Independent Artists, Writers, and Performers";
		break;
		case "711510":
		return "Independent Artists, Writers, and Performers";
		break;
		case "712":
		return "Museums, Historical Sites, and Similar Institutions";
		break;
		case "7121":
		return "Museums, Historical Sites, and Similar Institutions";
		break;
		case "71211":
		return "Museums";
		break;
		case "712110":
		return "Museums";
		break;
		case "71212":
		return "Historical Sites";
		break;
		case "712120":
		return "Historical Sites";
		break;
		case "71213":
		return "Zoos and Botanical Gardens";
		break;
		case "712130":
		return "Zoos and Botanical Gardens";
		break;
		case "71219":
		return "Nature Parks and Other Similar Institutions";
		break;
		case "712190":
		return "Nature Parks and Other Similar Institutions";
		break;
		case "713":
		return "Amusement, Gambling, and Recreation Industries";
		break;
		case "7131":
		return "Amusement Parks and Arcades";
		break;
		case "71311":
		return "Amusement and Theme Parks";
		break;
		case "713110":
		return "Amusement and Theme Parks";
		break;
		case "71312":
		return "Amusement Arcades";
		break;
		case "713120":
		return "Amusement Arcades";
		break;
		case "7132":
		return "Gambling Industries";
		break;
		case "71321":
		return "Casinos (except Casino Hotels)";
		break;
		case "713210":
		return "Casinos (except Casino Hotels)";
		break;
		case "71329":
		return "Other Gambling Industries";
		break;
		case "713290":
		return "Other Gambling Industries";
		break;
		case "7139":
		return "Other Amusement and Recreation Industries";
		break;
		case "71391":
		return "Golf Courses and Country Clubs";
		break;
		case "713910":
		return "Golf Courses and Country Clubs";
		break;
		case "71392":
		return "Skiing Facilities";
		break;
		case "713920":
		return "Skiing Facilities";
		break;
		case "71393":
		return "Marinas";
		break;
		case "713930":
		return "Marinas";
		break;
		case "71394":
		return "Fitness and Recreational Sports Centers";
		break;
		case "713940":
		return "Fitness and Recreational Sports Centers";
		break;
		case "71395":
		return "Bowling Centers";
		break;
		case "713950":
		return "Bowling Centers";
		break;
		case "71399":
		return "All Other Amusement and Recreation Industries";
		break;
		case "713990":
		return "All Other Amusement and Recreation Industries";
		break;
		case "72":
		return "Accommodation and Food Services";
		break;
		case "721":
		return "Accommodation";
		break;
		case "7211":
		return "Traveler Accommodation";
		break;
		case "72111":
		return "Hotels (except Casino Hotels) and Motels";
		break;
		case "721110":
		return "Hotels (except Casino Hotels) and Motels";
		break;
		case "72112":
		return "Casino Hotels";
		break;
		case "721120":
		return "Casino Hotels";
		break;
		case "72119":
		return "Other Traveler Accommodation";
		break;
		case "721191":
		return "Bed-and-Breakfast Inns";
		break;
		case "721199":
		return "All Other Traveler Accommodation";
		break;
		case "7212":
		return "RV (Recreational Vehicle) Parks and Recreational Camps";
		break;
		case "72121":
		return "RV (Recreational Vehicle) Parks and Recreational Camps";
		break;
		case "721211":
		return "RV (Recreational Vehicle) Parks and Campgrounds";
		break;
		case "721214":
		return "Recreational and Vacation Camps (except Campgrounds)";
		break;
		case "7213":
		return "Rooming and Boarding Houses";
		break;
		case "72131":
		return "Rooming and Boarding Houses";
		break;
		case "721310":
		return "Rooming and Boarding Houses";
		break;
		case "722":
		return "Food Services and Drinking Places";
		break;
		case "7221":
		return "Full-Service Restaurants";
		break;
		case "72211":
		return "Full-Service Restaurants";
		break;
		case "722110":
		return "Full-Service Restaurants";
		break;
		case "7222":
		return "Limited-Service Eating Places";
		break;
		case "72221":
		return "Limited-Service Eating Places";
		break;
		case "722211":
		return "Limited-Service Restaurants";
		break;
		case "722212":
		return "Cafeterias, Grill Buffets, and Buffets";
		break;
		case "722213":
		return "Snack and Nonalcoholic Beverage Bars";
		break;
		case "7223":
		return "Special Food Services";
		break;
		case "72231":
		return "Food Service Contractors";
		break;
		case "722310":
		return "Food Service Contractors";
		break;
		case "72232":
		return "Caterers";
		break;
		case "722320":
		return "Caterers";
		break;
		case "72233":
		return "Mobile Food Services";
		break;
		case "722330":
		return "Mobile Food Services";
		break;
		case "7224":
		return "Drinking Places (Alcoholic Beverages)";
		break;
		case "72241":
		return "Drinking Places (Alcoholic Beverages)";
		break;
		case "722410":
		return "Drinking Places (Alcoholic Beverages)";
		break;
		case "81":
		return "Other Services (except Public Administration)";
		break;
		case "811":
		return "Repair and Maintenance";
		break;
		case "8111":
		return "Automotive Repair and Maintenance";
		break;
		case "81111":
		return "Automotive Mechanical and Electrical Repair and Maintenance";
		break;
		case "811111":
		return "General Automotive Repair";
		break;
		case "811112":
		return "Automotive Exhaust System Repair";
		break;
		case "811113":
		return "Automotive Transmission Repair";
		break;
		case "811118":
		return "Other Automotive Mechanical and Electrical Repair and Maintenance";
		break;
		case "81112":
		return "Automotive Body, Paint, Interior, and Glass Repair";
		break;
		case "811121":
		return "Automotive Body, Paint, and Interior Repair and Maintenance";
		break;
		case "811122":
		return "Automotive Glass Replacement Shops";
		break;
		case "81119":
		return "Other Automotive Repair and Maintenance";
		break;
		case "811191":
		return "Automotive Oil Change and Lubrication Shops";
		break;
		case "811192":
		return "Car Washes";
		break;
		case "811198":
		return "All Other Automotive Repair and Maintenance";
		break;
		case "8112":
		return "Electronic and Precision Equipment Repair and Maintenance";
		break;
		case "81121":
		return "Electronic and Precision Equipment Repair and Maintenance";
		break;
		case "811211":
		return "Consumer Electronics Repair and Maintenance";
		break;
		case "811212":
		return "Computer and Office Machine Repair and Maintenance";
		break;
		case "811213":
		return "Communication Equipment Repair and Maintenance";
		break;
		case "811219":
		return "Other Electronic and Precision Equipment Repair and Maintenance";
		break;
		case "8113":
		return "Commercial and Industrial Machinery and Equipment (except Automotive and Electronic) Repair and Maintenance";
		break;
		case "81131":
		return "Commercial and Industrial Machinery and Equipment (except Automotive and Electronic) Repair and Maintenance";
		break;
		case "811310":
		return "Commercial and Industrial Machinery and Equipment (except Automotive and Electronic) Repair and Maintenance";
		break;
		case "8114":
		return "Personal and Household Goods Repair and Maintenance";
		break;
		case "81141":
		return "Home and Garden Equipment and Appliance Repair and Maintenance";
		break;
		case "811411":
		return "Home and Garden Equipment Repair and Maintenance";
		break;
		case "811412":
		return "Appliance Repair and Maintenance";
		break;
		case "81142":
		return "Reupholstery and Furniture Repair";
		break;
		case "811420":
		return "Reupholstery and Furniture Repair";
		break;
		case "81143":
		return "Footwear and Leather Goods Repair";
		break;
		case "811430":
		return "Footwear and Leather Goods Repair";
		break;
		case "81149":
		return "Other Personal and Household Goods Repair and Maintenance";
		break;
		case "811490":
		return "Other Personal and Household Goods Repair and Maintenance";
		break;
		case "812":
		return "Personal and Laundry Services";
		break;
		case "8121":
		return "Personal Care Services";
		break;
		case "81211":
		return "Hair, Nail, and Skin Care Services";
		break;
		case "812111":
		return "Barber Shops";
		break;
		case "812112":
		return "Beauty Salons";
		break;
		case "812113":
		return "Nail Salons";
		break;
		case "81219":
		return "Other Personal Care Services";
		break;
		case "812191":
		return "Diet and Weight Reducing Centers";
		break;
		case "812199":
		return "Other Personal Care Services";
		break;
		case "8122":
		return "Death Care Services";
		break;
		case "81221":
		return "Funeral Homes and Funeral Services";
		break;
		case "812210":
		return "Funeral Homes and Funeral Services";
		break;
		case "81222":
		return "Cemeteries and Crematories";
		break;
		case "812220":
		return "Cemeteries and Crematories";
		break;
		case "8123":
		return "Drycleaning and Laundry Services";
		break;
		case "81231":
		return "Coin-Operated Laundries and Drycleaners";
		break;
		case "812310":
		return "Coin-Operated Laundries and Drycleaners";
		break;
		case "81232":
		return "Drycleaning and Laundry Services (except Coin-Operated)";
		break;
		case "812320":
		return "Drycleaning and Laundry Services (except Coin-Operated)";
		break;
		case "81233":
		return "Linen and Uniform Supply";
		break;
		case "812331":
		return "Linen Supply";
		break;
		case "812332":
		return "Industrial Launderers";
		break;
		case "8129":
		return "Other Personal Services";
		break;
		case "81291":
		return "Pet Care (except Veterinary) Services";
		break;
		case "812910":
		return "Pet Care (except Veterinary) Services";
		break;
		case "81292":
		return "Photofinishing";
		break;
		case "812921":
		return "Photofinishing Laboratories (except One-Hour)";
		break;
		case "812922":
		return "One-Hour Photofinishing";
		break;
		case "81293":
		return "Parking Lots and Garages";
		break;
		case "812930":
		return "Parking Lots and Garages";
		break;
		case "81299":
		return "All Other Personal Services";
		break;
		case "812990":
		return "All Other Personal Services";
		break;
		case "813":
		return "Religious, Grantmaking, Civic, Professional, and Similar Organizations";
		break;
		case "8131":
		return "Religious Organizations";
		break;
		case "81311":
		return "Religious Organizations";
		break;
		case "813110":
		return "Religious Organizations";
		break;
		case "8132":
		return "Grantmaking and Giving Services";
		break;
		case "81321":
		return "Grantmaking and Giving Services";
		break;
		case "813211":
		return "Grantmaking Foundations";
		break;
		case "813212":
		return "Voluntary Health Organizations";
		break;
		case "813219":
		return "Other Grantmaking and Giving Services";
		break;
		case "8133":
		return "Social Advocacy Organizations";
		break;
		case "81331":
		return "Social Advocacy Organizations";
		break;
		case "813311":
		return "Human Rights Organizations";
		break;
		case "813312":
		return "Environment, Conservation and Wildlife Organizations";
		break;
		case "813319":
		return "Other Social Advocacy Organizations";
		break;
		case "8134":
		return "Civic and Social Organizations";
		break;
		case "81341":
		return "Civic and Social Organizations";
		break;
		case "813410":
		return "Civic and Social Organizations";
		break;
		case "8139":
		return "Business, Professional, Labor, Political, and Similar Organizations";
		break;
		case "81391":
		return "Business Associations";
		break;
		case "813910":
		return "Business Associations";
		break;
		case "81392":
		return "Professional Organizations";
		break;
		case "813920":
		return "Professional Organizations";
		break;
		case "81393":
		return "Labor Unions and Similar Labor Organizations";
		break;
		case "813930":
		return "Labor Unions and Similar Labor Organizations";
		break;
		case "81394":
		return "Political Organizations";
		break;
		case "813940":
		return "Political Organizations";
		break;
		case "81399":
		return "Other Similar Organizations (except Business, Professional, Labor, and Political Organizations)";
		break;
		case "813990":
		return "Other Similar Organizations (except Business, Professional, Labor, and Political Organizations)";
		break;
		case "814":
		return "Private Households";
		break;
		case "8141":
		return "Private Households";
		break;
		case "81411":
		return "Private Households";
		break;
		case "814110":
		return "Private Households";
		break;
		case "92":
		return "Public Administration";
		break;
		case "921":
		return "Executive, Legislative, and Other General Government Support";
		break;
		case "9211":
		return "Executive, Legislative, and Other General Government Support";
		break;
		case "92111":
		return "Executive Offices";
		break;
		case "921110":
		return "Executive Offices";
		break;
		case "92112":
		return "Legislative Bodies";
		break;
		case "921120":
		return "Legislative Bodies";
		break;
		case "92113":
		return "Public Finance Activities";
		break;
		case "921130":
		return "Public Finance Activities";
		break;
		case "92114":
		return "Executive and Legislative Offices, Combined";
		break;
		case "921140":
		return "Executive and Legislative Offices, Combined";
		break;
		case "92115":
		return "American Indian and Alaska Native Tribal Governments";
		break;
		case "921150":
		return "American Indian and Alaska Native Tribal Governments";
		break;
		case "92119":
		return "Other General Government Support";
		break;
		case "921190":
		return "Other General Government Support";
		break;
		case "922":
		return "Justice, Public Order, and Safety Activities";
		break;
		case "9221":
		return "Justice, Public Order, and Safety Activities";
		break;
		case "92211":
		return "Courts";
		break;
		case "922110":
		return "Courts";
		break;
		case "92212":
		return "Police Protection";
		break;
		case "922120":
		return "Police Protection";
		break;
		case "92213":
		return "Legal Counsel and Prosecution";
		break;
		case "922130":
		return "Legal Counsel and Prosecution";
		break;
		case "92214":
		return "Correctional Institutions";
		break;
		case "922140":
		return "Correctional Institutions";
		break;
		case "92215":
		return "Parole Offices and Probation Offices";
		break;
		case "922150":
		return "Parole Offices and Probation Offices";
		break;
		case "92216":
		return "Fire Protection";
		break;
		case "922160":
		return "Fire Protection";
		break;
		case "92219":
		return "Other Justice, Public Order, and Safety Activities";
		break;
		case "922190":
		return "Other Justice, Public Order, and Safety Activities";
		break;
		case "923":
		return "Administration of Human Resource Programs";
		break;
		case "9231":
		return "Administration of Human Resource Programs";
		break;
		case "92311":
		return "Administration of Education Programs";
		break;
		case "923110":
		return "Administration of Education Programs";
		break;
		case "92312":
		return "Administration of Public Health Programs";
		break;
		case "923120":
		return "Administration of Public Health Programs";
		break;
		case "92313":
		return "Administration of Human Resource Programs (except Education, Public Health, and Veterans' Affairs Programs)";
		break;
		case "923130":
		return "Administration of Human Resource Programs (except Education, Public Health, and Veterans' Affairs Programs)";
		break;
		case "92314":
		return "Administration of Veterans' Affairs";
		break;
		case "923140":
		return "Administration of Veterans' Affairs";
		break;
		case "924":
		return "Administration of Environmental Quality Programs";
		break;
		case "9241":
		return "Administration of Environmental Quality Programs";
		break;
		case "92411":
		return "Administration of Air and Water Resource and Solid Waste Management Programs";
		break;
		case "924110":
		return "Administration of Air and Water Resource and Solid Waste Management Programs";
		break;
		case "92412":
		return "Administration of Conservation Programs";
		break;
		case "924120":
		return "Administration of Conservation Programs";
		break;
		case "925":
		return "Administration of Housing Programs, Urban Planning, and Community Development";
		break;
		case "9251":
		return "Administration of Housing Programs, Urban Planning, and Community Development";
		break;
		case "92511":
		return "Administration of Housing Programs";
		break;
		case "925110":
		return "Administration of Housing Programs";
		break;
		case "92512":
		return "Administration of Urban Planning and Community and Rural Development";
		break;
		case "925120":
		return "Administration of Urban Planning and Community and Rural Development";
		break;
		case "926":
		return "Administration of Economic Programs";
		break;
		case "9261":
		return "Administration of Economic Program";
		break;
		case "92611":
		return "Administration of General Economic Programs";
		break;
		case "926110":
		return "Administration of General Economic Programs";
		break;
		case "92612":
		return "Regulation and Administration of Transportation Programs";
		break;
		case "926120":
		return "Regulation and Administration of Transportation Programs";
		break;
		case "92613":
		return "Regulation and Administration of Communications, Electric, Gas, and Other Utilities";
		break;
		case "926130":
		return "Regulation and Administration of Communications, Electric, Gas, and Other Utilities";
		break;
		case "92614":
		return "Regulation of Agricultural Marketing and Commodities";
		break;
		case "926140":
		return "Regulation of Agricultural Marketing and Commodities";
		break;
		case "92615":
		return "Regulation, Licensing, and Inspection of Miscellaneous Commercial Sectors";
		break;
		case "926150":
		return "Regulation, Licensing, and Inspection of Miscellaneous Commercial Sectors";
		break;
		case "927":
		return "Space Research and Technology";
		break;
		case "9271":
		return "Space Research and Technology";
		break;
		case "92711":
		return "Space Research and Technology";
		break;
		case "927110":
		return "Space Research and Technology";
		break;
		case "928":
		return "National Security and International Affairs";
		break;
		case "9281":
		return "National Security and International Affairs";
		break;
		case "92811":
		return "National Security";
		break;
		case "928110":
		return "National Security";
		break;
		case "92812":
		return "International Affairs";
		break;
		case "928120":
		return "International Affairs";
		break;
       
	}
	
}




?>
