/*
 * Copyright (c) Contributors, http://opensimulator.org/ See CONTRIBUTORS.TXT for a full list of copyright holders.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the OpenSim Project nor the names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE DEVELOPERS ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Funktion
Das Interface IMoneyServiceCore definiert grundlegende Service-Methoden f�r den Kern des MoneyServers. Es stellt folgende Funktionalit�ten bereit:

    Zugriff auf den HTTP-Server (GetHttpServer)
    Zugriff auf verschiedene Session-Dictionaries (GetSessionDic, GetSecureSessionDic, GetWebSessionDic)
    Zugriff auf Konfigurationen (GetServerConfig, GetCertConfig)
    Pr�fen, ob ein Client-Zertifikat gepr�ft wird (IsCheckClientCert)

Null-Pointer & Fehlerquellen
    Da es sich um ein Interface handelt, gibt es keine Implementierung und somit auch keine direkte Gefahr f�r NullPointerExceptions im Interface selbst.
    Die Gefahr von NullPointerExceptions entsteht erst in der konkreten Implementierung, wenn z.B. eine der Get*-Methoden null zur�ckliefert und der Aufrufer das Ergebnis nicht pr�ft.

Typische Fehlerquellen in der Implementierung:
    R�ckgabe von null bei Methoden, die Dictionaries oder Konfigurationen liefern sollen.
    Nicht initialisierte Objekte in der Implementierung dieser Methoden.
    Der HTTP-Server oder die Konfigurationen k�nnten in Implementierungen nicht korrekt gesetzt sein.

Empfehlungen f�r Implementierungen
    Immer sicherstellen, dass Methoden niemals null zur�ckliefern, au�er das Verhalten ist explizit dokumentiert und der Aufrufer pr�ft dies.
    Bei Fehlern oder fehlenden Objekten lieber leere Dictionaries oder Default-Konfigurationen zur�ckgeben.
    Gute Dokumentation, wann Methoden null liefern k�nnten.

Zusammenfassung
    Funktion: Interface zur Bereitstellung zentraler Kernfunktionen f�r den MoneyServer.
    Null-Pointer: Keine Gefahr im Interface selbst, aber m�glicherweise in der Implementierung, wenn R�ckgabewerte null sind.
    Empfehlung: In der Implementierung auf robuste R�ckgaben achten und keine null-Referenzen zur�ckgeben.
 */

using Nini.Config;

using OpenSim.Framework.Servers.HttpServer;

using System.Collections.Generic;


namespace OpenSim.Grid.MoneyServer
{
    public interface IMoneyServiceCore
    {
        /// <summary>Gets the HTTP server.</summary>
        BaseHttpServer GetHttpServer();

        /// <summary>Gets the session dic.</summary>
        Dictionary<string, string> GetSessionDic();

        /// <summary>Gets the secure session dic.</summary>
        Dictionary<string, string> GetSecureSessionDic();

        /// <summary>Gets the web session dic.</summary>
        Dictionary<string, string> GetWebSessionDic();

        /// <summary>Gets the server configuration.</summary>
        IConfig GetServerConfig();

        /// <summary>Gets the cert configuration.</summary>
        IConfig GetCertConfig();

        /// <summary>Determines whether [is check client cert].</summary>
        /// <returns>
        ///   <c>true</c> if [is check client cert]; otherwise, <c>false</c>.</returns>
        bool IsCheckClientCert();
    }
}
