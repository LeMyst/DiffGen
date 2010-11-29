<?php
// The list of patches to add to the diff
$patches = array(
    // "AdjustFontSize",
    "AllowChatFlood25Lines",
    "AllowChatFlood50Lines",
    "AllowChatFlood100Lines",
    "AllowMultipleWindows",
    // "BlackLoginBackground",
    // "ChangeVersionIntoDate",
    "CustomWindowTitle",
    "Disable1rag1N1sak1",
    // "Disable4LetterUserCharacterLimit",
    // "Disable4LetterUserIDLimit",
    // "Disable4LetterUserPasswordLimit",
    // "DisableCaptcha",
    // "DisableCharnameChatParsing",
    // "DisableEffect",
    "DisableFilenameCheck",
    "DisableHShield",
    // "DisableHallucinationWavyScreen",
    // "DisableLv99Aura",
    // "DisableMultipleWindows",
    "DisableSwearFilter",
    // "Enable127Hairstyles",
    // "EnableASCIIinText",
    // "EnableAuraOverLv99",
    // "EnableFlagEmotes",
    "EnableMultipleGRFs",
    //"EnableOfficialCustomFonts",
    "EnableQuestWindow",
    // "EnableShowname",
    "EnableTitleBarMenu",
    // "EnableWAndWhoCommands",
    // "EnforceIROFont",
    // "EnforceOfficialLoginBackground",
    // "ExitBattleModeonlywithSpace",
    "ExtendedChatBox",
    "ExtendedChatRoomBox",
    "ExtendedPMBox",
    // "FixBattleModeDoubleLetters",
    // "FixCameraAnglesFull",
    // "FixCameraAnglesLess",
    // "FixCameraAnglesRecomm",
    // "FixTradeWindowCrash",
    // "FreeFormStatsPolygon",
    // "GravityErrorHandler",
    // "GuildMessageCrashFix",
    // "HKLMtoHKCU",
    // "IgnoreChangedAlertMessages",
    // "IgnoreFileChecksum",
    // "IgnoreMissingFileErrors",
    // "IgnoreMissingPaletteErrors",
    // "IncreaseQualityScreenshotTo95per",
    // "IncreaseViewID",
    // "IncreaseZoomOut50Per",
    // "IncreaseZoomOut75Per",
    // "IncreaseZoomOutMax",
    // "InvalidEmailFix",
    // "KoreaServiceTypeXMLFix",
    "LoadLuaBeforeLub",
    // "LoginWindow",
    // "MultiLanguageSupport",
    // "OnlyFirstLoginBackground",
    // "OnlySecondLoginBackground",
    // "PlayOpenningDotBik",
    "ReadDataFolderFirst",
    "ReadMsgstringtabledottxt",
    // "RemoveGravityAds",
    // "RemoveGravityErrorMessage",
    // "RemoveGravityLogo",
    "RestoreLoginWindow",
    // "SSInBMPByDefault",
    // "ShowAllButtonsInLoginWindows",
    // "ShowExpBarsUpTo255",
    // "ShowLicenseScreenAlways",
    "SkipLicenseScreen",
    // "SkipResurrectionButtons",
    // "SkipServiceSelect",
    // "TranslateClientInEnglish",
    "UnlimitedChatFlood",
    // "UnlimitedLoadingScreens",
    "UseArialOnAllLangtypes",
    // "UseCustomAuraSprites",
    // "UseCustomFont",
    // "UseEncodedDescriptions",
    // "UseNormalGuildBrackets",
    // "UseOfficialClothesPalettes",
    "UsePlainTextDescriptions",
    // "UseRagnarokIcon",
);
foreach ($patches as $patch) {
    $exe = clone $src;
    Diff($src, $exe, $patch);
}
/*
$patches = array(
    "ChatAtBug",
    "EnableDNSSupport",
    "EnableMultipleGRFs",
    "EnableProxySupport",
    "GRFAdataBdataSupport",
    "SaveMainChatWithScrollLock",
    "SetTCPNODELAY",
    "ShowDebug",
    "UseSharedPalettes",
);
foreach ($patches as $patch) {
    $exe = clone $srcc;
    Diff($srcc, $exe, $patch);
    $srcc = clone $exe;
}

$patches_colors = array(
    "GmChatColor",
    "GuildChatColor",
    "MainChatColor",
    "OtherChatColor",
    "OtherPartyChatColor",
    "YourChatColor",
    "YourPartyChatColor",
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
    "FF0000", //Red
    "FFFF00", //Yellow
    "FFFFFF", //White
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
    "Red",
    "Yellow",
    "White",
);
foreach ($patches_colors as $patch) {
    $exe = clone $src;
    DiffColor($src, $exe, $patch);
}

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

$patch = "OFF_by_default_Skip";
$exe = clone $src;
Diff($src, $exe, $patch);
*/
?>
