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
    TransactionData ist eine reine Datenklasse (POCO/DTO), die alle relevanten Felder f�r eine Finanztransaktion im OpenSim-Money-Modul kapselt.
        Felder: UUIDs, Betr�ge, Balances, Typen, Zeiten, Status, Objekt- und Regionsdaten, Sicherheitscode, Beschreibung usw.
        F�r alle Felder gibt es Properties mit Getter/Setter.
    Zwei Enums:
        Status (SUCCESS, PENDING, FAILED, ERROR)
        AvatarType (diverse Avatar-Typen, z.B. LOCAL, HG, GUEST, NPC)
    UserInfo ist ebenfalls eine reine Datenklasse f�r Benutzerinformationen, mit Properties f�r UserID, SimIP, Name, Passwort-Hash, Typ, Klasse, ServerURL.

Null Pointer & Fehlerquellen
Null Pointer
    Felder vom Typ string werden grunds�tzlich mit string.Empty initialisiert (niemals null im Standardzustand).
    UUIDs werden (soweit gesetzt) aus dem Typ OpenMetaverse.UUID initialisiert � dieser Typ ist ein struct und kann daher nie null sein.
    Es gibt keine Methoden mit Logik, daher auch keine Parameterpr�fungen oder komplexe Objekt-Manipulationen, bei denen NullPointer auftreten k�nnten.
    Setter/Getter der Properties geben direkt das Feld zur�ck oder setzen es, ohne Logik.
    Die Enums k�nnen nicht null sein.

Fehlerquellen
    Keine Methoden mit Logik, also keine klassische Fehlerquelle wie Division durch 0, Indexfehler o.�.
    Setter f�hren keine Validierung durch (z.B. auf g�ltige UUIDs, Wertebereiche), 
    was bei fehlerhafter Nutzung zu inkonsistenten Daten f�hren k�nnte � das ist aber kein NullPointer und im Kontext von Datenklassen �blich.
    UserInfo: Alle Felder sind mit sinnvollen Defaultwerten versehen (string.Empty oder ein Enum-Wert).

Zusammenfassung
    Null Pointer: Es gibt im aktuellen Code keine Gefahr f�r NullPointer-Exceptions, da alle Felder mit Defaultwerten belegt sind und keine Logik existiert.
    Fehlerquellen: Keine Methoden, keine Validierung � klassisch f�r Datenklassen. 
    Fehler k�nnen nur entstehen, wenn von au�en ung�ltige Werte gesetzt werden, aber das verursacht keine NullPointer-Fehler.
    Funktion: Kapselt Daten f�r Transaktionen und Benutzer im OpenSim-Money-System.

Fazit:
Die Datei ist eine reine Datenstruktur und sicher gegen�ber NullPointer-Fehlern. Es gibt keine gef�hrlichen Stellen im Code. 
Die Nutzung ist robust, solange von au�en sinnvolle Werte gesetzt werden.
 */

using OpenMetaverse;


namespace OpenSim.Data.MySQL.MySQLMoneyDataWrapper
{
    public class TransactionData
    {
        UUID m_uuid;
        string m_sender = string.Empty;
        string m_receiver = string.Empty;
        int m_amount;
        int m_senderBalance;
        int m_receiverBalance;
        int m_type;
        int m_time;
        int m_status;
        string m_objectID = UUID.Zero.ToString();
        string m_objectName = string.Empty;
        string m_regionHandle = string.Empty;
        string m_regionUUID = string.Empty;
        string m_secureCode = string.Empty;
        string m_commonName = string.Empty;
        string m_description = string.Empty;

        /// <summary>Gets or sets the trans UUID.</summary>
        /// <value>The trans UUID.</value>
        public UUID TransUUID
        {
            get { return m_uuid; }
            set { m_uuid = value; }
        }

        /// <summary>Gets or sets the sender.</summary>
        /// <value>The sender.</value>
        public string Sender
        {
            get { return m_sender; }
            set { m_sender = value; }
        }

        /// <summary>Gets or sets the receiver.</summary>
        /// <value>The receiver.</value>
        public string Receiver
        {
            get { return m_receiver; }
            set { m_receiver = value; }
        }

        /// <summary>Gets or sets the amount.</summary>
        /// <value>The amount.</value>
        public int Amount
        {
            get { return m_amount; }
            set { m_amount = value; }
        }

        /// <summary>Gets or sets the sender balance.</summary>
        /// <value>The sender balance.</value>
        public int SenderBalance
        {
            get { return m_senderBalance; }
            set { m_senderBalance = value; }
        }

        /// <summary>Gets or sets the receiver balance.</summary>
        /// <value>The receiver balance.</value>
        public int ReceiverBalance
        {
            get { return m_receiverBalance; }
            set { m_receiverBalance = value; }
        }

        /// <summary>Gets or sets the type.</summary>
        /// <value>The type.</value>
        public int Type
        {
            get { return m_type; }
            set { m_type = value; }
        }

        /// <summary>Gets or sets the time.</summary>
        /// <value>The time.</value>
        public int Time
        {
            get { return m_time; }
            set { m_time = value; }
        }

        /// <summary>Gets or sets the status.</summary>
        /// <value>The status.</value>
        public int Status
        {
            get { return m_status; }
            set { m_status = value; }
        }

        /// <summary>Gets or sets the description.</summary>
        /// <value>The description.</value>
        public string Description
        {
            get { return m_description; }
            set { m_description = value; }
        }

        /// <summary>Gets or sets the object UUID.</summary>
        /// <value>The object UUID.</value>
        public string ObjectUUID
        {
            get { return m_objectID; }
            set { m_objectID = value; }
        }

        /// <summary>Gets or sets the name of the object.</summary>
        /// <value>The name of the object.</value>
        public string ObjectName
        {
            get { return m_objectName; }
            set { m_objectName = value; }
        }

        /// <summary>Gets or sets the region handle.</summary>
        /// <value>The region handle.</value>
        public string RegionHandle
        {
            get { return m_regionHandle; }
            set { m_regionHandle = value; }
        }

        /// <summary>Gets or sets the region UUID.</summary>
        /// <value>The region UUID.</value>
        public string RegionUUID
        {
            get { return m_regionUUID; }
            set { m_regionUUID = value; }
        }

        /// <summary>Gets or sets the secure code.</summary>
        /// <value>The secure code.</value>
        public string SecureCode
        {
            get { return m_secureCode; }
            set { m_secureCode = value; }
        }

        /// <summary>Gets or sets the name of the common.</summary>
        /// <value>The name of the common.</value>
        public string CommonName
        {
            get { return m_commonName; }
            set { m_commonName = value; }
        }
    }


    public enum Status
    {
        SUCCESS_STATUS = 0,
        PENDING_STATUS = 1,
        FAILED_STATUS = 2,
        ERROR_STATUS = 9
    }


    public enum AvatarType
    {
        LOCAL_AVATAR = 0,
        HG_AVATAR = 1,
        NPC_AVATAR = 2,
        GUEST_AVATAR = 3,
        FOREIGN_AVATAR = 8,
        UNKNOWN_AVATAR = 9
    }


    public class UserInfo
    {
        string m_userID = string.Empty;
        string m_simIP = string.Empty;
        string m_avatarName = string.Empty;
        string m_passwordHash = string.Empty;
        int m_avatarType = (int)AvatarType.LOCAL_AVATAR;
        int m_avatarClass = (int)AvatarType.LOCAL_AVATAR;
        string m_serverURL = string.Empty;

        /// <summary>Gets or sets the user identifier.</summary>
        /// <value>The user identifier.</value>
        public string UserID
        {
            get { return m_userID; }
            set { m_userID = value; }
        }

        /// <summary>Gets or sets the sim ip.</summary>
        /// <value>The sim ip.</value>
        public string SimIP
        {
            get { return m_simIP; }
            set { m_simIP = value; }
        }

        /// <summary>Gets or sets the avatar.</summary>
        /// <value>The avatar.</value>
        public string Avatar
        {
            get { return m_avatarName; }
            set { m_avatarName = value; }
        }

        /// <summary>Gets or sets the PSW hash.</summary>
        /// <value>The PSW hash.</value>
        public string PswHash
        {
            get { return m_passwordHash; }
            set { m_passwordHash = value; }
        }

        /// <summary>Gets or sets the type.</summary>
        /// <value>The type.</value>
        public int Type
        {
            get { return m_avatarType; }
            set { m_avatarType = value; }
        }

        /// <summary>Gets or sets the class.</summary>
        /// <value>The class.</value>
        public int Class
        {
            get { return m_avatarClass; }
            set { m_avatarClass = value; }
        }

        /// <summary>Gets or sets the server URL.</summary>
        /// <value>The server URL.</value>
        public string ServerURL
        {
            get { return m_serverURL; }
            set { m_serverURL = value; }
        }
    }
}
