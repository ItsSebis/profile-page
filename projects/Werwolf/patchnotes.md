# Alpha 0.1.0 (3.3.22)
 - added function for admins to create Test games to test functionality
 - delete test games after someone exits

# Alpha 0.0.9 (3.3.22)
 - added informative functions
   - getStati returns array of game stati in letters
   - getDeathMsg returns array of causes of death in letters
 - added the option to disable the primeval wolf
 - if player is logged in he will return to the last game he was in since it is still open
 - better title
 - shows specific stats for the logged in player

# Alpha 0.0.8 (1.3.22)
 - better tests for winner
 - added statistics if logged in
   - counts the times you´re a specific role
   - counts the times you´ve won on a specific side
   - counts your times played Werewolf
 - added setUserStat function

# Alpha 0.0.7 (25.2.22)
 - primeval wolf added
 - patchnotes added
 - improved Account linking
 - added primeval wolf functions
 - fixed werVotePlayers for primeval wolf
 - added functions:
   - gameLiving
   - playerDataLoggedIn
   - setGameHost
   - isLiving
 - added primeval wolf to generating roles
 - set new GameHost if old leaves
 - added exceptions
 - improved player needed count
 - check if role is living before getting to its turn

# Alpha 0.0.6 (24.2.22)
 - added Account Linking
 - clear Games if not in a game
 - unset session fixes
 - untilToday function

# Alpha 0.0.5 (22.2.22)
 - generate Roles fix
 - Amor skip if already loved

# Alpha 0.0.4 (21.2.22)
 - Amor added
 - resetGameValues fixes
 - Advanced players needed counter
 - "New Game" button at the end of a game

# Alpha 0.0.3 (21.2.22)
 - Test for a winner
 - Showing dead at the end of a game
 - Show Dead function
 - resetValues and resetGameValues fixes
 - session will unset if player is not reachable
 - werewolf voting fixes
 - Winner screen

# Alpha 0.0.2 (20.2.22)
 - requires now public functions
 - formatting and colors for roles
 - added functions of witches
 - added functions of werewolves
 - player dying and kill functions added
 - player vote functions added
 - player reset functions added
 - added Statistics
 - fixed security bugs
 - fixed autogeneration of roles

# Alpha 0.0.1 (19.2.22)
 - added Roles: Villager, Werewolf, Witch
 - following functions were added:
   - createGame
   - createPlayer
   - echoPlayers
   - werVotePlayers
   - gamePlayers
   - playerCount
   - gameData
   - playerDataByName
   - setGameStatus
   - setPlayerRole
   - generateRoles
 - set own nickname and join
 - start button
 - createGames
 - html basement
 - show joined players