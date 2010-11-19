<?php
// The list of patches to add to the diff
$patches = array(
	"AuraCrashfix",
	"FixClientFreeze",
	"IgnoreFileChecksum",
	"EnforceLoginPacket0x2b0",
	"DisableLoginPacket0x2b0",
	"DisableKeyCrypt",
	"DisableWantToConnectionXORing",
	"DisableWantToConnectionObfuscation",
	"GravityErrorHandler",
	"AdjustFontSize",
	"AllowChatFlood25Lines",
	"AllowChatFlood50Lines",
	"AllowChatFlood100Lines",
	"UnlimitedChatFlood",
	"ExtendedChatBox",
	"ExtendedPMBox",
	"ExtendedChatRoomBox",
	"DisableCharnameChatParsing",
	"CustomWindowTitle",
	"DisableLv99Aura",
	"EnableAuraOverLv99",
	"DisableSwearFilter",
	"EnableASCIIinText",
	"EnableFlagEmotes",
	"EnableQuestWindow",
	"EnableNewTradeWindow",
	"EnableNewCharSelectScreen",
	"EnableStatsOver99",
	"EnableWAndWhoCommands",
	"EnforceOfficialLoginBackground",
	"BlackLoginBackground",
	"FixCameraAnglesRecomm",
	"FixCameraAnglesLess",
	"FixCameraAnglesFull",
	"IgnoreChangedAlertMessages",
	"IgnoreMissingFileErrors",
	"IgnoreMissingPaletteErrors",
	"IncreaseQualityScreenshotTo95per",
	"IncreaseZoomOut50Per",
	"IncreaseZoomOut75Per",
	"IncreaseZoomOutMax",
	"PlayOpenningDotBik",
	"ShowAllButtonsInLoginWindows",
	"ShowExpBarsUpTo255",
	"ShowExpJobBarsUpTo255",
	"ShowLicenseScreenAlways",
	"SkipLicenceScreen",
	"SkipResurrectionButtons",
	"SkipServiceSelect",
	"UseArialOnAllLangtypes",
	"UseCustomFont",
	"UseNormalGuildBrackets",
	"UseRagnarokIcon",
	"EnforceIROFont",
	"EnableShowname",
	"DisableEffect",
);
foreach ($patches as $patch) {
	$exe = clone $src;
	Diff($src, $exe, $patch);
}

$patches_colors = array(
	"GmChatColor",
	"OtherChatColor",
	"MainChatColor",
	"YourChatColor",
	"YourPartyChatColor",
	"OtherPartyChatColor",
	"GuildChatColor",
);
$colors_numbers = array(
	"4169E6", //Blue
	"66CCFF", //LightBlue
	"96F096", //Green
	"CCFF00", //LightGreen
	"FA8C05", //Orange
	"FA1496", //Pink
	"9605D7", //Purple
	"5AA0A5", //Turquoise
);
$colors_name = array(
	"Blue",
	"LightBlue",
	"Green",
	"LightGreen",
	"Orange",
	"Pink",
	"Purple",
	"Turquoise",
);
foreach ($patches_colors as $patch) {
        $exe = clone $src;
        DiffColor($src, $exe, $patch);
}

$patches = array(
	"FreeFormStatsPolygon",
	"ReadDataFolderFirst",
	"ReadMsgstringtabledottxt",
	"UnlimitedLoadingScreens",
	"UseCustomAuraSprites",
);
foreach ($patches as $patch) {
	$exe = clone $src;
	Diff($src, $exe, $patch);
}

$patches = array(
	"GRFAdataBdataSupport",
	"EnableMultipleGRFs",
);

foreach ($patches as $patch) {
	$exe = clone $srcc;
	Diff($srcc, $exe, $patch);
}
$srcc = clone $exe;

$patches = array(
	"UseEncodedDescriptions",
	"UsePlainTextDescriptions",
	"UseOfficialClothesPalettes",
	"AllowMultipleWindows",
	"Disable1rag1N1sak1",
	"RemoveGravityLogo",
	"RemoveGravityAds",
	"Disable4LetterUserCharacterLimit",
	"Disable4LetterUserIDLimit",
	"Disable4LetterUserPasswordLimit",
	"DisableNProtectAndGameGuard",
	"DisableFilenameCheck",
	"Enable9CharacterSlots",
	"Enable12CharacterSlots",
	"Enable15CharacterSlots",
	"Enable18CharacterSlots",
	"InvalidEmailFix",
	"KoreaServiceTypeXMLFix",
);
foreach ($patches as $patch) {
	$exe = clone $src;
	Diff($src, $exe, $patch);
}

$patch = "SaveMainChatWithScrollLock";
$exe = clone $srcc;
Diff($srcc, $exe, $patch);
$srcc = clone $exe;

$patch = "ShowDebug";
$exe = clone $srcc;
Diff($srcc, $exe, $patch);
$srcc = clone $exe;

$patches = array(
	"TaekwonSLSGKoreantoEnglish",
	"CashPointsKoreantoEnglish",
	"ExitBattleModeonlywithSpace",
	"FixBattleModeDoubleLetters",
	"DisableHallucinationWavyScreen",
	"DisableEncryptationInLoginPacket0x2b0",
	"MultiLanguageSupport",
//	"BetaMultiLanguageSupport",
	"GuildMessageCrashFix",
	"FixTradeWindowCrash",
	"HKLMtoHKCU",

);
foreach ($patches as $patch) {
	$exe = clone $src;
	Diff($src, $exe, $patch);
}

$patch = "EnableDNSSupport";
$exe = clone $srcc;
Diff($srcc, $exe, $patch);
$srcc = clone $exe;

$patch = "EnableProxySupport";
$exe = clone $srcc;
Diff($srcc, $exe, $patch);
$srcc = clone $exe;

	//"CustomMobs", // Todo - Not Important

$autos_name = array(
	"ON__by_default_/Noshift",
	"ON__by_default_/Quickspell",
	"ON__by_default_/Quickspell2",
	"OFF_by_default_/Aura",
	"OFF_by_default_/Skillfail",
	"OFF_by_default_/Loginout",
	"ON__by_default_/Shopping_(Recommended)",
	"ON__by_default_/Notalkmsg",
	"ON__by_default_/Notalkmsg2",
	"ON__by_default_/Notrade",
	"ON__by_default_/Window_(Recommended)",
	"OFF_by_default_/Showname",
	"ON__by_default_/Loading",
);

$patch = "Autos";
$exe = clone $src;
DiffAutos($src, $exe, $patch);

$patches = array(
	"OFF_by_default_Skip",
	"XRayAllowCreateCustomPalettes",
	"XRayAllowCreateCustomHairstyle",
	"XRayExpandHomunculusandMercenaryIDs",
);
foreach ($patches as $patch) {
	$exe = clone $src;
	Diff($src, $exe, $patch);
}
?>
